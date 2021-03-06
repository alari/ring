var R = {
	UserMenu: {
		isShowed: 0,
		el: null,
		toggle: function(){
			this.isShowed ? this.hide() : this.show();
		},
		show: function(){
			R.Shadow.show();
			this.getEl().fade('show');
			this.isShowed = 1;
		},
		hide: function(){
			R.Shadow.hide();
			this.getEl().fade('hide');
			this.isShowed = 0;
		},
		getEl: function(){
			if(!this.el) {
				this.el = $('user-menu');
				this.el.style.width = $('user-box').getSize().x;
			}
			return this.el;
		}
	},
	Shadow: {
		el: null,
		isShowed:0,
		show: function(){
			this.getEl().style.width = $('wrap').getParent().getScrollSize().x;
			this.getEl().style.height = $('wrap').getParent().getScrollSize().y;
			this.getEl().fade(0.6);
			this.isShowed = 1;
		},
		hide: function(){
			this.getEl().fade("out");
			this.isShowed = 0;
		},
		getEl: function(){
			if(!this.el) {
				this.el = new Element("div", {id:"shadow"});
				this.el.fade('hide');
				$('wrap').getParent().adopt(this.el);
				this.el.style.left = 0;
				this.el.style.top = 0;
			}
			return this.el;
		},
		toggle: function(widthOffset){
			this.isShowed ? this.hide() : this.show(widthOffset);
		}
	},
	AjaxFragment: {
		show: function(el, options) {
			el = $(el);
			this.init(el);
			el.set("r_ajax_isShowed", 1);
			el.style.overflow = "hidden";
			new Request($merge(options,{onSuccess:function(response){
				el.set('html', response);
				el.get('tween', {property:'height'}).start(0,el.getScrollSize().y).chain(function(){el.style.height='auto';el.style.overflow='visible';});
			}})).send();
		},
		hide: function(el) {
			el = $(el);
			el.style.overflow='hidden';
			el.get('tween',{property:'height'}).start(el.getScrollSize().y, 0);
			el.set("r_ajax_isShowed", 0);
		},
		init: function(el, param) {
			if(el.get('r_ajax_isInitiated') == 1) return;
			el.style.height = 0;
			el.set('r_ajax_isInitiated', 1);
		},
		toggle: function(el, options) {
			el = $(el);
			if(el.get("r_ajax_isShowed") == 1) this.hide(el);
			else this.show(el, options);
		}
	},
	Comment: {
		showForm: function(el, url, root, parent, sys) {
			el = $(el).getParent();
			e=el.retrieve("form-el");
			if(!e) {
				var e=new Element('div');
				e.injectAfter(el);
				el.store("form-el", e);
			}
			if(e.get("r_ajax_isShowed") == 1) {
				return R.AjaxFragment.hide(e);
			}
			var action = parent?'comment-for':'comment-new';
			options = {url:url, data:{
				root: root,
				action: action,
				'parent-node': parent,
				sys: sys},
				evalScripts:true};
			R.AjaxFragment.show(e, options);
		},
		remove: function(url, root, comm, sys) {
			if(!confirm("Удалить комментарий со всеми ответами на него?")) return;
			$('comm-'+comm).fade(0.5);
			$('comm-add-'+comm).fade(0.4);
			new Request.JSON({url:url, data:{
				root: root,
				action: 'delete',
				comm: comm,
				sys: sys}, onSuccess:function(response){
					if(response.status == "FAILED") {
						alert("Ошибка! Удалить комментарий не удалось.");
						return;
					}
					for(j in response.comments) {
						i = response.comments[j];
						$('comm-'+i).dispose();
						$('comm-add-'+i).dispose();
					}
				}}).send();
		}
	},
	System: {
		url: null,
		setSortable: function(list, handle, host) {
			this.url = 'http://'+host+'/admin/system-position';
			new Sortables(list, {'handle':handle, onStart:R.SortableUtils.onStart, onComplete:this.onComplete.bind(this)});
		},
		onComplete:function(el){
			el = $(el);
			 var newPosition = el.getAllPrevious().length+1;
			 if(newPosition == R.SortableUtils.oldPosition-1) return;
		 	
		 	var elId = el.get("id").replace(/^sysid-(.+)$/, "$1");
		 	new Request({url:this.url,data:{base:elId,pos:newPosition}}).post();
		 }
	},
	Collection: {
		url: null,
		setSortable: function(list, handle, host) {
			this.url = 'http://'+host+'/admin/collection-position';
			new Sortables(list, {'handle':handle, onStart:R.SortableUtils.onStart, onComplete:this.onComplete.bind(this)});
		},
		onComplete:function(el){
			el = $(el);
			 var newPosition = el.getAllPrevious().length+1;
			 if(newPosition == R.SortableUtils.oldPosition-1) return;
		 	
		 	var elId = el.get("id").replace(/^collid-([0-9]+)$/, "$1");
		 	new Request({url:this.url,data:{coll:elId,pos:newPosition}}).post();
		 }
	},
	Anonce: {
		url: null,
		setSortable: function(list, handle, host) {
			this.url = 'http://'+host+'/admin/anonce-position';
			new Sortables(list, {'handle':handle, onStart:R.SortableUtils.onStart, onComplete:this.onComplete.bind(this)});
		},
		onComplete: function(el) {
			 el = $(el);
			 var newPosition = el.getAllPrevious().length+1;
			 if(newPosition == R.SortableUtils.oldPosition-1) return;
			 	
			 var elId = el.get("id").replace(/^anonceid-(.+)$/, "$1");
			 new Request({url:this.url,data:{anonce:elId,pos:newPosition}}).post();
		}
	},
	SortableUtils: {
		oldPosition: null,
		onStart: function(el) {
			el = $(el);
			R.SortableUtils.oldPosition = el.getAllPrevious().length+1; 
		}
	}
};
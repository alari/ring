var R = {
	UserMenu: {
		isShowed: 0,
		el: null,
		toggle: function(){
			this.isShowed ? this.hide() : this.show();
		},
		show: function(){
			R.Shadow.show();
			this.getEl().fadeIn();
			this.isShowed = 1;
		},
		hide: function(){
			R.Shadow.hide();
			this.getEl().fadeOut();
			this.isShowed = 0;
		},
		getEl: function(){
			if(!this.el) {
				this.el = $('#user-menu');
				this.el.css('width', $('#user-box').width());
			}
			return this.el;
		}
	},
	Shadow: {
		el: null,
		isShowed:0,
		show: function(){
			this.el.css('width', $('#wrap').width());
			this.el.css('height', $('#wrap').height());
			this.getEl().fadeTo(400, 0.6);
			this.isShowed = 1;
		},
		hide: function(){
			this.getEl().fadeTo("fast", 0, function(){
				$(this).fadeOut();
			});
			this.isShowed = 0;
		},
		getEl: function(){
			if(!this.el) {
				this.el = new Element("div", {id:"shadow"});
				this.el.fadeOut();
				$('#wrap').after(this.el);
				this.el.style.left = 0;
				this.el.style.top = 0;
			}
			return this.el;
		},
		toggle: function(){
			this.isShowed ? this.hide() : this.show();
		}
	},
	AjaxFragment: {
		show: function(elName, options) {
			el = $('#'+elName);
			this.init(el);
			el.attr("r_ajax_isShowed", 1);
			el.css("overflow", "hidden");
			
			el.load(options.url, options.data, function() {
				$(this).animate({				 
				    height: $(this).innerHeight()
				  }, 850, function() {
					  $(this).css('height', 'auto');
					  $(this).css('overflow', 'visible');
				  });
			});
		},
		hide: function(el) {
			el = $('#'+el);
			el.css('overflow', 'hidden');
			el.animate({height: $(this).innerHeight()}, 850);
			el.attr("r_ajax_isShowed", 0);
		},
		init: function(el, param) {
			if(el.attr('r_ajax_isInitiated') == 1) return;
			el.css('height', 0);
			el.attr('r_ajax_isInitiated', 1);
		},
		toggle: function(elName, options) {
			el = $("#" + elName);
			if(el.attr("r_ajax_isShowed") == 1) this.hide(elName);
			else this.show(elName, options);
		}
	},
	Comment: {
		/*
		 * .showForm(this,'<?=O_UrlBuilder::get( "comment" )?>',<?=$rootId?>,<?=$parent?>,<?=$systemId?>)
		 */
		showForm: function(el, url, root, parent, sys) {
			el = $(el).parent();
			e = el.children('.form-el');
			if(e.lenght == 0) {
				el.children('.form-el').after('<div class="form-el"></div>');
			}
			if(e.attr("r_ajax_isShowed") == 1) {
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
			$('#comm-'+comm).fadeTo(400, 0.5);
			$('#comm-add-'+comm).fadeTo(400, 0.4);
			$.post(url, {
				root: root,
				action: 'delete',
				comm: comm,
				sys: sys}, function(response){
					if(response.status == "FAILED") {
						alert("Ошибка! Удалить комментарий не удалось.");
						return;
					}
					for(j in response.comments) {
						i = response.comments[j];
						$('#comm-'+i).dispose();
						$('#comm-add-'+i).dispose();
					}
				},
				"json"
			);
		}	
	},
	System: {
		url: null,
		setSortable: function(list, handle, host) {\
			R.SortableUtils.init();
			this.url = 'http://'+host+'/admin/system-position';
			$(list).Sortable({
				start:R.SortableUtils.onStart,
				update: this.onComplete,
				handle: handle
			});
		},
		onComplete:function(elName){
			el = $("#"+elName);
			var newPosition = el.prevUntil().length+1;
			if(newPosition == R.SortableUtils.oldPosition-1) return;
		 	
		 	var elId = el.get("id").replace(/^sysid-(.+)$/, "$1");
		 	$.post(this.url, {base:elId, pos:newPosition});
		 }
	},
	Collection: {
		url: null,
		setSortable: function(list, handle, host) {
			R.SortableUtils.init();
			this.url = 'http://'+host+'/admin/collection-position';
			$(list).Sortable({
				start:R.SortableUtils.onStart,
				update: this.onComplete,
				handle: handle
			});
		},
		onComplete:function(el){
			el = $("#"+elName);
			var newPosition = el.prevUntil().length+1;
			if(newPosition == R.SortableUtils.oldPosition-1) return;
		 	
			var elId = el.get("id").replace(/^collid-([0-9]+)$/, "$1");
			$.post(this.url, {base:elId, pos:newPosition});
		 }
	},
	Anonce: {
		url: null,
		setSortable: function(list, handle, host) {
			R.SortableUtils.init();
			this.url = 'http://'+host+'/admin/anonce-position';
			$(list).Sortable({
				start:R.SortableUtils.onStart,
				update: this.onComplete,
				handle: handle
			});
		},
		onComplete: function(elName) {
			 el = $("#"+elName);
			 var newPosition = el.prevUntil().length+1;
			 if(newPosition == R.SortableUtils.oldPosition-1) return;
			 	
			 var elId = el.get("id").replace(/^anonceid-(.+)$/, "$1");
			 $.post(this.url, {base:elId, pos:newPosition});
		}
	},
	SortableUtils: {
		oldPosition: null,
		onStart: function(elName) {
			el = $("#"+elName);
			R.SortableUtils.oldPosition = el.prevUntil().length+1; 
		},
		init: function(){
			$.getScript('http://centralis.name/static/js/jquery-ui-min.js');
		}
	
	}
};
var R = {
	UserMenu: {
		isShowed: 0,
		el: null,
		toggle: function(){
			this.isShowed ? this.hide() : this.show();
		},
		show: function(){
			R.Shadow.show($('login-box').getSize().x);
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
				this.el.style.width = $('login-box').getSize().x;
			}
			return this.el;
		}
	},
	Shadow: {
		el: null,
		isShowed:0,
		show: function(widthOffset){
			this.getEl().style.width = $('wrapper').getParent().getScrollSize().x-widthOffset;
			this.getEl().style.height = $('wrapper').getParent().getScrollSize().y;
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
				$('wrapper').getParent().adopt(this.el);
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
		}
	}
};
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
	}
};
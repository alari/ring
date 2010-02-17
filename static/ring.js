var R = {
	UserMenu: {
		isShowed: 0,
		el: null,
		toggle: function(){
			R.UserMenu.isShowed ? this.hide() : this.show();
		},
		show: function(){
			this.getEl().fadeIn();
			R.Shadow.show();
			R.UserMenu.isShowed = 1;
		},
		hide: function(){
			R.Shadow.hide();
			this.getEl().fadeOut();
			R.UserMenu.isShowed = 0;
		},
		getEl: function(){
			if(!R.UserMenu.el) {
				R.UserMenu.el = $('#user-menu');
				R.UserMenu.el.css('width', $('#user-box').width());
				//R.userMenu.el
			}
			return R.UserMenu.el;
		}
	},
	Shadow: {
		el: null,
		isShowed:0,
		show: function(){

			this.getEl().css('width', $('#wrap').width());
			R.Shadow.el.css('height', $('#wrap').height());
			R.Shadow.el.fadeTo(400, 0.6);
			R.Shadow.isShowed = 1;
		},
		hide: function(){
			this.getEl().fadeTo("fast", 0, function(){
				$(this).fadeOut();
			});
			R.Shadow.isShowed = 0;
		},
		getEl: function(){
			if(!R.Shadow.el) {
				R.Shadow.el = $("<div id='shadow'></div>");
				R.Shadow.el.fadeOut();
				$('#wrap').after(R.Shadow.el);
				R.Shadow.el.css("left", 0);
				R.Shadow.el.css("top", 0);
			}
			return R.Shadow.el;
		},
		toggle: function(){
			this.isShowed ? this.hide() : this.show();
		}
	},
	AjaxFragment: {
		show: function(elName, options) {
			
			el = typeof(elName)=='object' ? elName : $('#'+elName);
			this.init(el);
			el.attr("r_ajax_isShowed", 1);
			el.css("overflow", "hidden");
			
			el.load(options.url, options.data).animate({				 
				    height: $(this).innerHeight()
				  }, 850, function() {
					  $(this).css('height', 'auto');
					  $(this).css('overflow', 'visible');
				  });

		},
		hide: function(el) {
			el = typeof(el)=='object' ? el : $('#'+el);
			el.css('overflow', 'hidden');
			el.animate({height: 0}, 850);
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
			if(e.length == 0) {
				el.append('<div class="form-el"></div>');
				e = $('.form-el', el);
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
		setSortable: function(list, handle, host) {
			//R.SortableUtils.init(function(list, handle, host){});
			this.url = 'http://'+host+'/admin/system-position';
			$(list).sortable({
				start:R.SortableUtils.onStart,
				update: this.onComplete,
				//handle: handle
			});
		},
		onComplete:function(e, elObj){
			el = elObj.item;
			var newPosition = el.prevUntil().length+1;
			if(newPosition == R.SortableUtils.oldPosition) return;
		 	
		 	var elId = el.attr("id").replace(/^sysid-(.+)$/, "$1");
		 	$.post(R.System.url, {base:elId, pos:newPosition});
		 }
	},
	Collection: {
		url: null,
		setSortable: function(list, handle, host) {
		//	R.SortableUtils.init();
			this.url = 'http://'+host+'/admin/collection-position';
			$(list).sortable({
				start:R.SortableUtils.onStart,
				update: this.onComplete,
				//handle: handle
			});
		},
		onComplete:function(e, elObj){
			el = elObj.item;
			var newPosition = el.prevUntil().length+1;
			if(newPosition == R.SortableUtils.oldPosition) return;
		 	
			var elId = el.attr("id").replace(/^collid-([0-9]+)$/, "$1");
			$.post(R.Collection.url, {base:elId, pos:newPosition});
		 }
	},
	Anonce: {
		url: null,
		setSortable: function(list, handle, host) {
			//R.SortableUtils.init();
			this.url = 'http://'+host+'/admin/anonce-position';
			$(list).sortable({
				start:R.SortableUtils.onStart,
				update: this.onComplete,
				//handle: handle
			});
		},
		onComplete: function(ev, elObj) {
			 el = elObj.item;
			 var newPosition = el.prevUntil().length+1;
			 if(newPosition == R.SortableUtils.oldPosition) return;
			 	
			 var elId = el.attr("id").replace(/^anonceid-(.+)$/, "$1");
			 $.post(R.Anonce.url, {base:elId, pos:newPosition});
		}
	},
	SortableUtils: {
		oldPosition: null,
		onStart: function(ev, elObj) {
			el = elObj.item;
			R.SortableUtils.oldPosition = el.prevUntil().length+1; 
		}
		/*init: function(cb){
			$.getScript('http://centralis.name/static/js/jquery-ui-min.js', cb);
		}*/
	
	}
};

var AdminInfo = function() {
	return {
		init: function() {
			$("#left-nav-info-manager").attr("class", $("#left-nav-info-manager").attr("class") + " active");
		},

		initIndex: function() {

			Util.initBatchDelete("info");
		},

		initCreate: function() {
			$("#form-info").validate({
				rules:{
					key: {
						required:true,
						maxlength:255,
			            remote:{
			              url: "/admin/util/check/info",
			              type: "post",
			              dataType: "json",
			              data:{
			                _token: function(){return $("meta[name=csrf-token]").attr("content");},
			                field: function(){return $("#key").attr("name");},
			                value: function(){return $("#key").val();}
			              }
			            }
					},
					value:{
						required:true,
						maxlength:2000
					}
				}
			});
		},

		initEdit: function() {
			$("#form-info").validate({
				rules:{
					key: {
						required:true,
						maxlength:255,
			            remote:{
			              url: "/admin/util/check/info",
			              type: "post",
			              dataType: "json",
			              data:{
			                _token: function(){return $("meta[name=csrf-token]").attr("content");},
			                id: function(){return $("#id").val();},
			                field: function(){return $("#key").attr("name");},
			                value: function(){return $("#key").val();}
			              }
			            }
					},
					value:{
						required:true,
						maxlength:2000
					}
				}
			});
		},
	};
}();
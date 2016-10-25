var AdminUser = function() {
	return {
		init: function() {
			$("#left-nav-user-manager").attr("class", $("#left-nav-user-manager").attr("class") + " active");
		},

		initIndex: function(type) {
			$("#type").val(type);

			Util.initBatchDelete("user");
		},

		initCreate: function() {
			$("#form-user").validate({
				rules:{
					name: {
						maxlength:255
					},
					email:{
						required:true,
						maxlength:255,
						email:true,
			            remote:{
			              url: "/admin/util/check/user",
			              type: "post",
			              dataType: "json",
			              data:{
			                _token: function(){return $("meta[name=csrf-token]").attr("content");},
			                field: function(){return $("#email").attr("name");},
			                value: function(){return $("#email").val();}
			              }
			            }
					},
					password:{
						required:true,
						maxlength:30
					},
					password2:{
						required:true,
						maxlength:30,
						equalTo: "#password"
					}
				}
			});
		},

		initEdit: function(type) {
			$("#type").val(type);

			$("#form-user").validate({
				rules:{
					name: {
						maxlength:255
					},
					email:{
						required:true,
						maxlength:255,
						email:true,
			            remote:{
			              url: "/admin/util/check/user",
			              type: "post",
			              dataType: "json",
			              data:{
			                _token: function(){return $("meta[name=csrf-token]").attr("content");},
			                id: function(){return $("#id").val();},
			                field: function(){return $("#email").attr("name");},
			                value: function(){return $("#email").val();}
			              }
			            }
					},
					password:{
						maxlength:30
					},
					password2:{
						maxlength:30,
						equalTo: "#password"
					}
				}
			});
		},/**/
	};
}();
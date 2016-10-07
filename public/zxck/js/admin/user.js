var AdminUser = function() {
	return {
		init: function() {
			$("#left-nav-user-manager").attr("class", $("#left-nav-user-manager").attr("class") + " active");
		},

		initIndex: function() {
			$("#batch_delete").click(function(){
				var _token = $("meta[name=csrf-token]").attr("content"); 
				//console.log(_token);
				var checkboxs = document.getElementsByName("checkbox[]");
		        //console.log(checkboxs);
		        var arr_checkbox = new Array();
		        var j = 0;
		        for(var i = 0; i < checkboxs.length; i++){
		        	//console.log(checkboxs[i].checked);
		        	if(checkboxs[i].checked == true){
		        		arr_checkbox[j] = checkboxs[i].getAttribute("data-id");
		        		j++;
		        	}
		        }
		        //console.log(arr_checkbox);

		          $.post("/admin/util/batch-delete/user",
		              {
		                  _token : _token,
		                  ids : arr_checkbox
		              },
		              function(data, status){
		                  if(data == "true"){
		                      window.location.reload();
		                  }else{
		                      alert("删除失败");
		                  }
		              }
		          );
			});
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
var AdminInfo = function() {
	return {
		init: function() {
			$("#left-nav-info-manager").attr("class", $("#left-nav-info-manager").attr("class") + " active");
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

		          $.post("/admin/util/batch-delete/info",
		              {
		                  _token : _token,
		                  ids : arr_checkbox
		              },
		              function(data, status){
		                  if(data == "true"){
		                  		layer.msg('删除成功');
		                      window.location.reload();
		                  }else{
		                      //alert("删除失败");
		                      layer.msg('删除失败');
		                  }
		              }
		          );
			});
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
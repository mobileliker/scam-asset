var AdminContent = function() {
	return {
		init: function() {
			//console.log('content');
			$("#a-admin-settings").click(function() {
				//console.log('click');
				layer.open({
				  type: 1,
				  title: '用户设置',
				  skin: 'layui-layer-rim', //加上边框
				  area: ['600px', '300px'], //宽高
				  content: $('#admin-settings')
				});
			});


			$("#form-suser").validate({
				rules:{
					sname: {
						maxlength:255
					},
					spassword:{
						maxlength:30
					},
					spassword2:{
						maxlength:30,
						equalTo: "#spassword"
					}
				}
			});

			$("#form-suser").submit(function(){
				//console.log('submit');
	            $('#form-suser').ajaxSubmit(function(data) { 
	            	//console.log(data);
	            	if(data == "false"){
	            		layer.msg('保存失败');
	            	}else{
	            		layer.closeAll();
	            		layer.msg('保存成功');
	            		//console.log(data.name);
	            		$("#a-admin-name").html(data.name + "<span class='caret'></span>");
	            	}
	            }); 
				return false;
			});
		},
	};
}();
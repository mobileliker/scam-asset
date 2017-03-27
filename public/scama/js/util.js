var Util = function() {
	return {
		initOrder: function(sort, order) {
			//console.log("sort=" + sort);
			//console.log('order=' + order);
			var t1 = $("[data-sort='" + sort + "']");
			if(order == "desc") t1.attr("data-order", "asc");
			else t1.attr("data-order", "desc");
			if(order == "desc") t1.html(t1.html() + "&nbsp;<i class='fa fa-sort-desc' aria-hidden='true'></i>");
			else t1.html(t1.html() + "&nbsp;<i class='fa fa-sort-asc' aria-hidden='true'></i>");

			$(".admin-order-group").click(function() {
				//console.log('click');
				//console.log($(this).attr("data-sort"));
				//console.log($(this).attr('data-order'));
				$("#_sort").val($(this).attr("data-sort"));
				$("#_order").val($(this).attr("data-order"));
				$(".form-admin-search").submit();
			});
		},

		initBatchDelete: function(module) {

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

				$.post("/admin/util/batch-delete/" + module,
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
	};
}();
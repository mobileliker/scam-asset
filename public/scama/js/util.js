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
	};
}();
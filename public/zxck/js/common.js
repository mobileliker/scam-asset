
//全选
$(".checkbox-all").click(function(){
	console.log('good');
	var state = $(this)[0].checked;
	var group = $(this).attr("data-group-name");
	var checkboxs = $("[data-group='" + group + "']");
	for(var i = 0; i < checkboxs.length; i++){
		checkboxs[i].checked = state;
	}
});
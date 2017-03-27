var AdminCategory = function () {
	return {
		init: function() {
			$("#left-nav-category-manager").attr("class", $("#left-nav-category-manager").attr("class") + " active");
		},

		initIndex: function (pid) {

			$("#pid").val(pid);

			Util.initBatchDelete("category");

		},

		initCreate: function () {
			$("#form-category").validate({
				rules: {
					pid: {
						digits: true,
						min: 0
					},
					name: {
						required: true,
						maxlength: 255
					},
					value: {
						required: true,
						maxlength: 255,
					},
					serial: {
						required: true,
						maxlength: 255,
						remote: {
							url: "/admin/util/check/category",
							type: "post",
							dataType: "json",
							data:{
								_token: function(){return $("meta[name=csrf-token]").attr("content");},
								field: function(){return $("#serial").attr("name");},
								value: function(){return $("#serial").val();}
			              	}
						}
					}
				}
			});
		},

		initEdit: function (pid) {
			$("#pid").val(pid);


			$("#form-category").validate({
				rules: {
					pid: {
						digits: true,
						min: 0
					},
					name: {
						required: true,
						maxlength: 255
					},
					value: {
						required: true,
						maxlength: 255,
					},
					serial: {
						required: true,
						maxlength: 255,
						remote: {
							url: "/admin/util/check/category",
							type: "post",
							dataType: "json",
							data:{
								_token: function(){return $("meta[name=csrf-token]").attr("content");},
								id: function(){return $("#id").val();},
								field: function(){return $("#serial").attr("name");},
								value: function(){return $("#serial").val();}
			              	}
						}
					}
				}
			});
		},

	};
}();
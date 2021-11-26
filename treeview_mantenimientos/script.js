//jQuery(document).ready(function(){
//	jQuery.ajax({
//		url: "<?php echo base_url(); ?>/treeview_mantenimientos/tree_data.php",
//		cache: false,
//		success: function(response){
//                    alert(response);
//                    //$('#treeview').treeview({data: response});
//		}
//	});	
//});


jQuery(document).ready(function(){
	jQuery.ajax({
		url: "<?php echo base_url(); ?>/treeview_mantenimientos/tree_data.php",
		cache: false,
		success: function(response){
                    alert("Exito");
                    //$('#treeview').treeview({data: response});
		},
		error: function(response){
                    alert("Error");
                    //$('#treeview').treeview({data: response});
		}
	});	
});
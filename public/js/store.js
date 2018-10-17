$(document).ready(function(){
	$('#ajaxSubmit').click(function(e){
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
			}
		});
		jQuery.ajax({
			url: "{{ url('/grocery/post') }}",
			method: 'post',
			data: {
				name: jQuery('#name').val(),
				type: jQuery('#type').val(),
				price: jQuery('#price').val()
			},
			success: function(result){
				console.log(result);
			}
		});
	});
});

/*
function storeStatus(e, currentStatusCount) {
	alert("here");
	e.preventDefault();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
		}
	});
	$.ajax({
		url: "{{ url('/current-status/post') }}",
		method: 'post',
		data: {
			status: $('#cs-' + (currentStatusCount - 1)).val()
		},
		success: function(result){
			console.log(result);
		}
	});
}
*/
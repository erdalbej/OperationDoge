$(document).ready(function(){

	$("tbody tr").click(function (event) {    
		var parent = $(event.target).parent();

		var data = {};
		data.dogName = $(parent).children().eq(0).text();
		data.officialName = $(parent).children().eq(1).text();
		data.color = $(parent).children().eq(2).text();
		data.breader = $(parent).children().eq(3).text();
		data.weight = $(parent).children().eq(3).text();
		data.height = $(parent).children().eq(4).text();
		data.mental = $(parent).children().eq(5).text();
		data.birthdate = $(parent).children().eq(6).text();
		data.dogImgPath = $(parent).children().eq(7).text();
		data.genImgPath = $(parent).children().eq(8).text();
		data.description = $(parent).children().eq(9).text();

		console.log(data);

		$("#edit-dog-name").val(data.dogName);
		$("#edit-official-name").val(data.officialName);
		
		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(300);
		
	})


})
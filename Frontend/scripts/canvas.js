var sketcher = atrament(document.getElementById('canvasPaint'),500,500);
sketcher.weight = 3;
sketcher.smoothing = false;

document.getElementById("crearFigura").onclick = function() {crearFigura()};
document.getElementById("limpiarCanvas").onclick=function(){limpiar()};

function crearFigura() {
	var c=document.getElementById("canvasPaint");
	var ctx=c.getContext("2d");
	var p1x = $('#punto1x').val();
	var p1y = $('#punto1y').val();
	var p2x = $('#punto2x').val();
	var p2y = $('#punto2y').val();
	var p3x = $('#punto3x').val();
	var p3y = $('#punto3y').val();
	var p4x = $('#punto4x').val();
	var p4y = $('#punto4y').val();

	ctx.lineWidth = 4;
	ctx.strokeStyle = '#000000';
	//ctx.clearRect(0, 0, 500, 500);
	if (p4x==0&&p4y==0) {
		ctx.beginPath();
		ctx.moveTo(p1x, p1y);
		ctx.lineTo(p2x, p2y);
		ctx.lineTo(p3x, p3y);
		ctx.closePath();
		 
		// the outline	
		ctx.stroke();
	} 
	else if (p4x!=0&&p4y!=0) {
		ctx.beginPath();
		ctx.moveTo(p1x, p1y);
		ctx.lineTo(p2x, p2y);
		ctx.lineTo(p3x, p3y);
		ctx.lineTo(p4x, p4y);
		ctx.closePath();

		ctx.stroke();
	}
}

function limpiar(){
	var c=document.getElementById("canvasPaint");
	var ctx=c.getContext("2d");
	ctx.clearRect(0, 0, 500, 500);
	location.reload();
	//sketcher.clear();
}

function clasificar()
{
	$("#limpiarCanvas").attr("disabled", true);
	$("#clasificarCanvas").attr("disabled", true);

	var img = sketcher.toImage();

	$.ajax({
       url: '../Backend/clasificarFigura.php',
       data: {image: img},
       type: "POST"
	})
	.done(function( data, textStatus, jqXHR ) 
    {
		$("#limpiarCanvas").attr("disabled", false);
		$("#clasificarCanvas").attr("disabled", false);

		console.log(data);

		if (data.indexOf("arning") >= 0)
		{
			alert("Dibuje algo primero");	
		}
		else
		{
			alert(data);
		}	
    })
    .fail(function( jqXHR, textStatus, errorThrown ) 
    {
		$("#limpiarCanvas").attr("disabled", false);
		$("#clasificarCanvas").attr("disabled", false);
    });
}
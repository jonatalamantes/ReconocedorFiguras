var sketcher = atrament(document.getElementById('canvasPaint'),500,500);

document.getElementById("crearFigura").onclick = function() {crearFigura()};
document.getElementById("limpiarCanvas").onclick=function(){limpiar()};

function crearFigura() {
	var c=document.getElementById("canvasPaint");
	var ctx=c.getContext("2d");
	var l1 = $('#lado1').val();
	var l2 = $('#lado2').val();
	var l3 = $('#lado3').val();
	ctx.clearRect(0, 0, 500, 500);
	if (l1!=0&&l2!=0&&l3!=0) {
		ctx.beginPath();
		ctx.moveTo(20, 20);
		ctx.lineTo(20, l1);
		ctx.lineTo(l1, l2);
		ctx.closePath();
		 
		// the outline
		ctx.lineWidth = 4;
		ctx.strokeStyle = '#666666';
		ctx.stroke();
	} 
	else if (l1!=0&&l2!=0&&l3==0) {
		ctx.rect(20,20,l2,l1);
		ctx.stroke();
	}
	else if (l1!=0&&l2==0&&l3==0) {
		ctx.rect(20,20,l1,l1);
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


function mostrar(variable){

	switch(variable){
	case 2: 
		document.getElementById('deporte2').style.display = 'block';
                document.getElementById('categoria2').style.display = 'block';
                document.getElementById('botones1').style.display = 'none';
                document.getElementById('botones2').style.display = 'block';
                
		break;
	case 3:
		document.getElementById('deporte3').style.display = 'block';
                document.getElementById('categoria3').style.display = 'block';
                document.getElementById('boton3').style.display = 'none';
		break;
	}
	
}
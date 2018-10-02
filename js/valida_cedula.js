function check_cedula(cedula ,id){
array = cedula.split( "" );
num = array.length;
if(num == 10){
total = 0;
digito = (array[9]*1);
for( i=0; i < (num-1); i++ ){
  mult = 0;
  if (( i%2 ) != 0 ) {
	total = total + ( array[i] * 1 );
  }
  else{
	mult = array[i] * 2;
	if ( mult > 9 )
	  total = total + ( mult - 9 );
	else
	  total = total + mult;
  }
}
decena = total / 10;
decena = Math.floor( decena );
decena = ( decena + 1 ) * 10;
final = ( decena - total );
if( ( final == 10 && digito == 0 ) || ( final == digito ) ) {
  return true;
}
else{
  alert( "El Numero de c\xe9dula NO es v\xe1lida!!!" );
  $(id).focus();
  return false;
}
}
else{
alert("El Numero de c\xe9dula no puede tener menos de 10 d\xedgitos");
 $(id).focus();
return false;
}
}
var table,tr,td,input,group,n,nbre=0,nbre2=0,form,ret,div;

function plus(){

    nbre++;
    nbre2++;
    div=document.getElementById('div_id');
    div.style.display='block';
    table=document.getElementById('tableprescription');
    tr=document.createElement('tr');
    tr.setAttribute('id','ligneprescription'+nbre);
    table.appendChild(tr);
    n=document.getElementById('nbreprescription');

	for(var i=1;i<7;i++){
	
		td=document.createElement('td');
		tr.appendChild(td);	
		input=document.createElement('input');
		
		if(i==6)
		{
			input.setAttribute('type','button');
			input.setAttribute('value','Retirer');
			input.setAttribute('class','btn btn-round btn-danger');
			input.style.border='none';
			input.style.cursor='pointer';
			input.setAttribute('onclick','moins(this)');
			input.setAttribute('title','Retirer');
			input.setAttribute('name','ligneprescription'+nbre);
			input.setAttribute('id','boutonBesoin'+nbre);
		}
		else
		{
			input.setAttribute('type','text');
			input.setAttribute('class','form-control col-md-1');
			source=document.getElementById('ligne'+i);
			input.value=source.value;
			
			if(i==1){ input.setAttribute('name','natureprestation'+nbre);
				input.setAttribute('readonly', 'readonly');
				input.setAttribute('id','natureprestation'+nbre);
			}
			if(i==2){
				input.setAttribute('name','prescription'+nbre);
				input.setAttribute('id','prescription'+nbre);
				input.setAttribute('required','required');
			}
			if(i==3){
				input.setAttribute('name','total_prestation'+nbre);
				input.setAttribute('id','total_prestation'+nbre);
				input.setAttribute('required','required');
			}
			if(i==4) {
				input.setAttribute('name','total_benef'+nbre);
				input.setAttribute('id','total_benef'+nbre);
			}
			if(i==5) {
				input.setAttribute('name','total_mutuelle'+nbre);
				input.setAttribute('id','total_mutuelle'+nbre);
			}
		}
		td.appendChild(input);
	}
	n.setAttribute('Value',nbre);
	
	document.getElementById('ligne1').value='';
	document.getElementById('ligne2').value='';
	document.getElementById('ligne3').value='';
	document.getElementById('ligne4').value='';
	document.getElementById('ligne5').value='';
}

function moins(ligneprescription1){

    if(document.getElementById(ligneprescription1))document.getElementById(ligneprescription1).parentNode.removeChild(document.getElementById(ligneprescription1));

    table=document.getElementById('tableprescription');
    tr=document.createElement('tr');
    tr.setAttribute('id','ligneprescription1');
    table.appendChild(tr);
	
	ret=document.getElementById(arg.name);
	alert(ret);
	table.removeChild(ret);
	nbre2--;
	if(nbre2==0){
		form.style.display='none';
	}
}
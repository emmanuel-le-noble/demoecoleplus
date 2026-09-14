function submit1(form2) {
//var t = ;

alert(document.form2.action.value);

document.form2.submit();
}

function afficher_1(ordre){
if(ordre.checked)
{
document.getElementById("type_activite").style.display="block";
document.getElementById("client1").style.display="none";
document.getElementById("client3").style.display="none";
}
}

function consulter_1(client_1)
{
if(client_1.checked)
{
document.getElementById("client").style.display="inline";
document.getElementById("client_1").style.display="none";
document.getElementById("client_2").style.display="none";
}
}

function consulter_2(client_1)
{
if(client_1.checked)
{
document.getElementById("client").style.display="none";
document.getElementById("client_1").style.display="inline";
document.getElementById("client_2").style.display="none";
}
}

function consulter_3(client_1)
{
if(client_1.checked)
{
document.getElementById("client").style.display="none";
document.getElementById("client_1").style.display="none";
document.getElementById("client_2").style.display="inline";
}
}



function makeRequest(url,id_niveau,id_ecrire)
{
	var http_request = false;
	//créer une instance (un objet) de la classe désirée fonctionnant sur plusieurs navigateurs
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		http_request = new XMLHttpRequest();
		if (http_request.overrideMimeType) {
			http_request.overrideMimeType('text/xml');//un appel de fonction supplémentaire pour écraser l'en-tête envoyé par le serveur, juste au cas où il ne s'agit pas de text/xml, pour certaines versions de navigateurs Mozilla
		}
	} else if (window.ActiveXObject) { // IE
		try {
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}

	if (!http_request) {
		alert('Abandon :( Impossible de créer une instance XMLHTTP');
		return false;
	}
	http_request.onreadystatechange = function() { traitementReponse(http_request,id_ecrire); } //affectation fonction appelée qd on recevra la reponse
	// lancement de la requete
	http_request.open('POST', url, true);
	//changer le type MIME de la requête pour envoyer des données avec la méthode POST ,  !!!! cette ligne doit etre absolument apres http_request.open('POST'....
	http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	obj=document.getElementById(id_niveau);
	data="val_sel="+obj.value;
	http_request.send(data);
}

function makeRequest_00(url,id_niveau,id_ecrire)
{
	var http_request = false;
	//créer une instance (un objet) de la classe désirée fonctionnant sur plusieurs navigateurs
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		http_request = new XMLHttpRequest();
		if (http_request.overrideMimeType) {
			http_request.overrideMimeType('text/xml');//un appel de fonction supplémentaire pour écraser l'en-tête envoyé par le serveur, juste au cas où il ne s'agit pas de text/xml, pour certaines versions de navigateurs Mozilla
		}
	} else if (window.ActiveXObject) { // IE
		try {
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}

	if (!http_request) {
		alert('Abandon :( Impossible de créer une instance XMLHTTP');
		return false;
	}
	http_request.onreadystatechange = function() { traitementReponse(http_request,id_ecrire); } //affectation fonction appelée qd on recevra la reponse
	// lancement de la requete
	http_request.open('FILE', url, true);
	//changer le type MIME de la requête pour envoyer des données avec la méthode POST ,  !!!! cette ligne doit etre absolument apres http_request.open('POST'....
	http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	obj=document.getElementById(id_niveau);
	data="val_sel="+obj.value;
	http_request.send(data);
}

function traitementReponse(http_request,id_ecrire) {
	var affich="";
	if (http_request.readyState == 4) 
	{
		if (http_request.status == 200) 
		{
		    // cas avec reponse de PHP en mode texte:
			//chargement des elements reçus dans la liste
			var affich_list=http_request.responseText;
			obj = document.getElementById(id_ecrire); 
            obj.innerHTML = affich_list;
		} 
		else 
		{
            alert('Un problème est survenu avec la requête.');
        }
    }
}




function makeReq(url,id_niveau,id_ecrire){
	var http_request = false;
	document.getElementById('id_list3').style.display='block';
		//créer une instance (un objet) de la classe désirée fonctionnant sur plusieurs navigateurs
        if (window.XMLHttpRequest) { // Mozilla, Safari,...
            http_request = new XMLHttpRequest();
            if (http_request.overrideMimeType) {
                http_request.overrideMimeType('text/xml');//un appel de fonction supplémentaire pour écraser l'en-tête envoyé par le serveur, juste au cas où il ne s'agit pas de text/xml, pour certaines versions de navigateurs Mozilla
            }
        } else if (window.ActiveXObject) { // IE
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }

        if (!http_request) {
            alert('Abandon :( Impossible de créer une instance XMLHTTP');
            return false;
        }
        http_request.onreadystatechange = function() { traitementReponse(http_request,id_ecrire); } //affectation fonction appelée qd on recevra la reponse
		// lancement de la requete
		http_request.open('POST', url, true);
		//changer le type MIME de la requête pour envoyer des données avec la méthode POST ,  !!!! cette ligne doit etre absolument apres http_request.open('POST'....
		http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		//obj=id_niveau;
		data="val_sel="+id_niveau;
        http_request.send(data);
}

function traitementReponse(http_request,id_ecrire) {
	var affich="";
	if (http_request.readyState == 4) {
		if (http_request.status == 200) 
		{
			// cas avec reponse de PHP en mode texte:
			//chargement des elements reçus dans la liste
			var affich_list=http_request.responseText;
		    obj = document.getElementById(id_ecrire); 
            obj.innerHTML = affich_list;
		} 
		else 
		{
            alert('Un problème est survenu avec la requête.');
        }
    }
}



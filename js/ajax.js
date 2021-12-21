//Ajax

var httpRequest;

function ajax_callback() {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
        if (httpRequest.status === 200) {
            var res=httpRequest.responseText;
            document.getElementById("callback").innerHTML=res;
        } else {
            alert('Il y a eu un problème avec la requête.');
        }
    }
}

function ajax_req(action,params){
    httpRequest = new XMLHttpRequest();
    if (!httpRequest){alert('error httprequest'); return false;}
    httpRequest.onreadystatechange = ajax_callback;
    httpRequest.open('GET', '../public/ajax.php?action='+action+'&params='+params, true);
    httpRequest.send();
}

//document.getElementById("ajaxButton").addEventListener('click', makeRequest_cities);

function ajax_call(el){
    const action=el.dataset.action;
    const params=el.dataset.params;
    ajax_req(action,params);
}

function capture_select(id){
    var ob=document.getElementById(id); var rc=[];
	for(var io=0;io<ob.length;io++){if(ob[io].checked)rc.push(ob[io].value);}
	return rc.join('-');
}


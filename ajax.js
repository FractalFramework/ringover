//Ajax

var httpRequest;
var _URL;

//output
function ajax_callback(target){
if(httpRequest.readyState===XMLHttpRequest.DONE){
	if(httpRequest.status===200){
		var res=httpRequest.responseText;
		document.getElementById(target).innerHTML=res;} 
	else alert('error httprequest');}}

function ajax_req(target,action,url,fd){
	httpRequest=new XMLHttpRequest();
	if(!httpRequest){alert('error httprequest'); return false;}
	httpRequest.onreadystatechange=function(){ajax_callback(target);}
	httpRequest.open('POST',url,true);
	httpRequest.send(fd);}

//enter point
function ajax_call(target,action,form){
	var ra=action.split('_');//get_city
	var fd=captures(form);
	//construct the url
	var url='/'+ra[0]+'/cities';
	if(_URL)url+=_URL; //console.log(_URL);
	ajax_req(target,action,url,fd);}

function captures(name){_URL='';
	var ob=document.forms[name];
	//we decide to store url in first select
	//console.log((ob[0].type).split('-')[0]);
	if(ob!=undefined && (ob[0].type).split('-')[0]=='select')_URL=ob[0].options[ob[0].selectedIndex].value;
	var fd=new FormData();
	if(ob)for(i=0;i<ob.length;i++){
		var ty=ob[i].type.split('-')[0];
		if(ty=='text' || ty=='number')fd.append(ob[i].name,ob[i].value);
		else if(ty=='select')fd.append(ob[i].name,ob[i].options[ob[i].selectedIndex].value);}
	return fd;}

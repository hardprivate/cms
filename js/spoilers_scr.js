function openClose(id0,id,src_plu,src_min)
{
var obj = "";
var obj0 = "";

c1111 = new Image(); c1111.src = src_plu;
c2222 = new Image(); c2222.src = src_min;

// Check browser compatibility
if(document.getElementById)
{
obj = document.getElementById(id).style;
obj0 = document.getElementById(id0);
}
else if(document.all)
{
obj = document.all[id];
obj0 = document.all[id0];
}
else if(document.layers)
{
obj = document.layers[id];
obj0 = document.layers[id0];
}
else return 1;

// Do the magic 
if(obj.display == "")
{
obj.display = "none";
obj0.src = c1111.src; 
}
else if(obj.display != "none")
{
obj.display = "none";
obj0.src = c1111.src; 
}
else
 {
 obj.display = "block";
 obj0.src = c2222.src; 
 }
}
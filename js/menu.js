function doMenu(id)
{
	if(document.getElementById(id).style.display == 'inherit')
	{
		document.getElementById(id).style.display = 'none';
	}
	else
	{
		document.getElementById(id).style.display = 'inherit';
	}
}

function doMenuOver(id)
{
	document.getElementById(id).style.cursor = 'pointer';
}

function doMenuOut(id)
{
	document.getElementById(id).style.cursor = 'default';
}
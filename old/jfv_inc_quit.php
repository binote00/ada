<script type="text/javascript">
function goodbye(e) {
	if(!e) e = window.event;
	//e.cancelBubble is supported by IE - this will kill the bubbling process.
	e.cancelBubble = true;
	e.returnValue = 'Etes-vous certain de vouloir faire cela ? <br> Vous risquez de perdre les données de votre session de jeu en cours.';

	//e.stopPropagation works in Firefox.
	if (e.stopPropagation) {
		e.stopPropagation();
		e.preventDefault();
	}
}
window.onbeforeunload=goodbye;
</script>
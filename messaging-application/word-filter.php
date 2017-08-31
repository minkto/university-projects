<?php

//This function allows you to add any word that you wish to be banned.

// For the application developed messages that contain inappropriate
// words, will be replaced with a specified character.

/**
 * 
 * @param  $message - Any text that should be cleaned should be put through here.
 * @return  This will return a censored version of a message
 */
function filterWords($message){
	{
		$filter_terms = array('/\bass(es|holes?)?\b/i','/\bshit(e|ted|ting|ty|head?)?\b/i',

				'/\banal\b/i',
				'/\banus\b/i',
				'/\bass\b/i',
				'/\bballsack\b/i',
				'/\bballs\b/i',
				'/\bbastard\b/i',
				'/\bbitch\b/i',
				'/\bbiatch\b/i',
				'/\bbloody\b/i',
				'/\bblowjob\b/i',
				'/\bblow job\b/i',
				'/\bbollock\b/i',
				'/\bbollok\b/i',
				'/\bboner\b/i',
				'/\bboob\b/i',
				'/\bbugger\b/i',
				'/\bbum\b/i',
				'/\bbutt\b/i',
				'/\bbuttplug\b/i',
				'/\bclitoris\b/i',
				'/\bcock\b/i',
				'/\bcoon\b/i',
				'/\bcrap\b/i',
				'/\bcunt\b/i',
				'/\bdamn\b/i',
				'/\bdick\b/i',
				'/\bdildo\b/i',
				'/\bdyke\b/i',
				'/\bfag\b/i',
				'/\bfeck\b/i',
				'/\bfellate\b/i',
				'/\bfellatio\b/i',
				'/\bfelching\b/i',
				'/\bfuck\b/i',
				'/\bf u c k\b/i',
				'/\bfudgepacker\b/i',
				'/\bfudge packer\b/i',
				'/\bflange\b/i',
				'/\bGoddamn\b/i',
				'/\bGod damn\b/i',
				'/\bhell\b/i',
				'/\bhomo\b/i',
				'/\bjerk\b/i',
				'/\bjizz\b/i',
				'/\bknobend\b/i',
				'/\bknob end\b/i',
				'/\blabia\b/i',
				'/\blmao\b/i',
				'/\blmfao\b/i',
				'/\bmuff\b/i',
				'/\bnigger\b/i',
				'/\bnigga\b/i',
				'/\bomg\b/i',
				'/\bpenis\b/i',
				'/\bpiss\b/i',
				'/\bpoop\b/i',
				'/\bprick\b/i',
				'/\bpube\b/i',
				'/\bpussy\b/i',
				'/\bqueer\b/i',
				'/\bscrotum\b/i',
				'/\bsex\b/i',
				'/\bs hit\b/i',
				'/\bsh1t\b/i',
				'/\bslut\b/i',
				'/\bsmegma\b/i',
				'/\bspunk\b/i',
				'/\btit\b/i',
				'/\btosser\b/i',
				'/\bturd\b/i',
				'/\btwat\b/i',
				'/\bvagina\b/i',
				'/\bwank\b/i',
				'/\bwhore\b/i',
				'/\bwtf\b/i'
		);
		$message = preg_replace($filter_terms, '***', $message);
		return $message;
	}
	
}

?>
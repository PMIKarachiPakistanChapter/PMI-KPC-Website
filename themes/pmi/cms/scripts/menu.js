$(document).ready(
	function(){
		//chapter document
		$('#ChapterDocuments').mouseover(
			function(){
				$('#ChapterDocuments div.sub_menu ul').css('display','');
			}
		);
		$('#ChapterDocuments').mouseout(
			function(){
				$('#ChapterDocuments div.sub_menu ul').css('display','none');
			}
		);

		//PMI Knowledge
		$('#PMIKnowledge').mouseover(
			function(){
				$('#PMIKnowledge div.sub_menu ul').css('display','');
			}
		);
		$('#PMIKnowledge').mouseout(
			function(){
				$('#PMIKnowledge div.sub_menu ul').css('display','none');
			}
		);

		//Membership
		$('#Membership').mouseover(
			function(){
				$('#Membership div.sub_menu ul').css('display','');
			}
		);
		$('.menu#Membership').mouseout(
			function(){
				$('#Membership div.sub_menu ul').css('display','none');
			}
		);

		//Board Members
		$('#BoardMembers').mouseover(
			function(){
				$('#BoardMembers div.sub_menu ul').css('display','');
			}
		);
		$('#BoardMembers').mouseout(
			function(){
				$('#BoardMembers div.sub_menu ul').css('display','none');
			}
		);


	}
);
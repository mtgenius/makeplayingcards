javascript:
if (location.host === 'scryfall.com') {
  var url = location.pathname.match(/\/card\/([^/]+\/[^/]+)\//)[1];
  location.href = 'http://docker:13207/index.php?url=' + url;
}
else {
  var cardImage = document.getElementById('ctl00_ctl00_ctl00_MainContent_SubContent_SubContent_cardImage');
  var cardTypes = document.getElementById('ctl00_ctl00_ctl00_MainContent_SubContent_SubContent_typeRow').getElementsByClassName('value').item(0).innerText;
  var creature = cardTypes.match(/Creature/) ? true : false;
  var multiverseid = cardImage.getAttribute('src').match(/multiverseid=(\d+)/)[1];
  var box =
    confirm('Hide copyright?') ?
      'box=' + (creature ? 'creature' : '') + '&' :
      '';
  cardImage.setAttribute(
    'src',
    'http://docker:13207/index.php?' + box + 'multiverseid='  + multiverseid
  );
}
void(0);

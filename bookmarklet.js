javascript:

if (location.host === 'scryfall.com') {

  var isCreature =
    /(Creature|Planeswalker)/.test(
      document
        .getElementsByClassName('card-text-type-line')
        .item(0)
        .innerText
    );

  var url =
    'url=' +
      document.getElementsByClassName('card-image-front').item(0)
        .getElementsByTagName('img').item(0)
        .getAttribute('src');
}
else {

  var isCreature =
    document
      .getElementById('ctl00_ctl00_ctl00_MainContent_SubContent_SubContent_typeRow')
      .getElementsByClassName('value')
      .item(0)
      .innerText
      .match(/Creature/) !== null;

  var url =
    'multiverseid=' +
    document.getElementById('ctl00_ctl00_ctl00_MainContent_SubContent_SubContent_cardImage')
      .getAttribute('src')
      .match(/multiverseid=(\d+)/)[1];

}

var box =
  confirm('Hide copyright?') ?
    'box=' + (isCreature ? 'creature' : '') + '&' :
    '';

location.href = 'http://docker:13207/index.php?' + box + url;

void(0);

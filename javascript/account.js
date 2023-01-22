function ToggleOverview() {
document.getElementById('Overview').style.visibility = 'visible';
document.getElementById('Profile').style.visibility = 'hidden';
document.getElementById('analytics').style.visibility = 'hidden';
document.getElementById('Savings').style.visibility = 'hidden';

}

function ToggleProfile() {
document.getElementById('Profile').style.visibility = 'visible';
document.getElementById('Savings').style.visibility = 'hidden';
document.getElementById('Services').style.visibility = 'hidden';
document.getElementById('Overview').style.visibility = 'hidden';
}

function ToggleSavings() {
document.getElementById('Savings').style.visibility = 'visible';
document.getElementById('Profile').style.visibility = 'hidden';
document.getElementById('analytics').style.visibility = 'hidden';
document.getElementById('Overview').style.visibility = 'hidden';
}

function ToggleServices() {
document.getElementById('analytics').style.visibility = 'visible';
document.getElementById('Savings').style.visibility = 'hidden';
document.getElementById('Profile').style.visibility = 'hidden';
document.getElementById('Overview').style.visibility = 'hidden';
}

function ToggleEdit(){
var size = document.getElementsByClassName("DialogInput").length;
for (var i = 0; i < size; i++) {
  document.getElementsByClassName('DialogInput')[i].style.pointerEvents = 'auto';
  document.getElementsByClassName('DialogInput')[i].style.backgroundColor = 'white';
}
document.getElementById('closebut').style.visibility = 'visible';
document.getElementById('editbut').style.visibility = 'hidden';
document.getElementById('subbut').style.visibility = 'visible';
}

function ToggleClose(){
  var size = document.getElementsByClassName("DialogInput").length;
  for (var i = 0; i < size; i++) {
    document.getElementsByClassName('DialogInput')[i].style.pointerEvents = 'none';
    document.getElementsByClassName('DialogInput')[i].style.backgroundColor = '#ededed';
  }
  document.getElementById('closebut').style.visibility = 'hidden';
  document.getElementById('editbut').style.visibility = 'visible';
  document.getElementById('subbut').style.visibility = 'hidden';
}

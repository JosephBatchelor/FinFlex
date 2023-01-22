var count = 1;
var entry1 = document.getElementById('FirstEntry');
var entry2 = document.getElementById('SecondEntry');
var entry3 = document.getElementById('ThirdEntry');
console.log(count);

function Left(){
if (count <=1) {
}else {
  count--;
}

  if (count == 1) {
    document.getElementById('FirstEntry').style.visibility = 'visible';
    document.getElementById('SecondEntry').style.visibility = 'hidden';
    document.getElementById('ThirdEntry').style.visibility = 'hidden';
    document.getElementById('left').style.visibility = 'hidden';
  }else if (count == 2) {
    document.getElementById('FirstEntry').style.visibility = 'hidden';
    document.getElementById('SecondEntry').style.visibility = 'visible';
    document.getElementById('ThirdEntry').style.visibility = 'hidden';
    document.getElementById('right').style.visibility = 'visible';
    document.getElementById('left').style.visibility = 'visible';
    document.getElementById('signbutton').style.visibility = 'hidden';
  }else if (count == 3) {
    document.getElementById('FirstEntry').style.visibility = 'hidden';
    document.getElementById('SecondEntry').style.visibility = 'hidden';
    document.getElementById('ThirdEntry').style.visibility = 'visible';
  }
}

function Right(){
  if (count >= 3) {
  }else {
    count++;
  }

  if (count == 1) {
    document.getElementById('FirstEntry').style.visibility = 'visible';
    document.getElementById('SecondEntry').style.visibility = 'hidden';
    document.getElementById('ThirdEntry').style.visibility = 'hidden';
  }else if (count == 2) {
    document.getElementById('FirstEntry').style.visibility = 'hidden';
    document.getElementById('SecondEntry').style.visibility = 'visible';
    document.getElementById('ThirdEntry').style.visibility = 'hidden';
    document.getElementById('right').style.visibility = 'visible';
    document.getElementById('left').style.visibility = 'visible';
    document.getElementById('signbutton').style.visibility = 'hidden';

  }else if (count == 3) {
    document.getElementById('FirstEntry').style.visibility = 'hidden';
    document.getElementById('SecondEntry').style.visibility = 'hidden';
    document.getElementById('ThirdEntry').style.visibility = 'visible';
    document.getElementById('right').style.visibility = 'hidden';
    document.getElementById('signbutton').style.visibility = 'visible';
  }
}

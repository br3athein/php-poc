'use strict';

function resetPic(path) {
    document.getElementById('thepic').src = path;
}

function resetBorder () {
    document.getElementById('thepic').border = 16;
};

function xpandBorder () {
    var thepic = document.getElementById('thepic');
    thepic.border = Number(thepic.border) + Number(xpandBy.value);
};

function revealSecret () {
    document.getElementById('hiddendiv').style.display = 'block';
};

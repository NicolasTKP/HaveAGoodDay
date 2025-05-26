function extend(){
    let navBackground = document.querySelector('navibar background');
    let naviBar = document.querySelectorAll('extend-bar span');
    if (navBackground.style.transform === 'translateX(-100%)') {
        navBackground.style.transform = 'translateX(0%)';
        // navBackground.style.borderTopRightRadius = '50px';
        // navBackground.style.borderBottomRightRadius = '50px';
        naviBar[0].style.transform='rotate(225deg) translateY(-1dvh) translateX(-1dvh)';
        naviBar[1].style.transform='rotate(225deg)';
        naviBar[1].style.display='none';
        naviBar[2].style.transform='rotate(315deg) translateY(0.1dvh) translateX(-0.2dvh)';
        
        
    } else {
        navBackground.style.transform = 'translateX(-100%)';
        // navBackground.style.borderTopRightRadius = '0px';
        // navBackground.style.borderBottomRightRadius = '0px';
        naviBar[0].style.transform='rotate(0deg)';
        naviBar[1].style.transform='rotate(0deg)';
        naviBar[1].style.display='block';
        naviBar[2].style.transform='rotate(0deg)';
        
    }
}

function showLine(element, width) {
    var line = document.getElementById(element);
    if (line) {
        line.style.width = width;
    }
}

function hideLine(element) {
    var line = document.getElementById(element);
    line.style.width = "0";
}

function redirect(location){
    window.location.href = location;
}
document.body.addEventListener("keydown", function (event) {
    event.preventDefault();
    switch (event.key) {
        case "ArrowDown":
            window.location.assign('boat/direction/S');
            break;
        case "ArrowUp":
            window.location.assign('boat/direction/N');
            break;
        case "ArrowLeft":
            window.location.assign('boat/direction/W');
            break;
        case "ArrowRight":
            window.location.assign('boat/direction/E');
            break;
    }
});
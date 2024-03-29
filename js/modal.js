class Modal {

    constructor(id) {
        this.elem = document.getElementById(id);
        this.loadHideListeners();
    };

    show(callback) {
        //this.elem.style.opacity = 1;
        this.elem.classList.remove('slit-out');
        this.elem.classList.add('slit-in');
        //this.elem.style.display = "block";
        if (callback) callback();
    };

    hide(callback) {
        this.elem.classList.remove('slit-in');
        this.elem.classList.add('slit-out');
        //this.elem.style.display = "none";
        this.createCloseEvent();
        if (callback) callback();
    };

    createCloseEvent() {
        let event = new Event('AzrModalClose');
        this.elem.classList.remove('slit-in');
       // this.elem.classList.remove('slit-out');
        document.dispatchEvent(event);
    }

    // Create event listeners for close modal
    loadHideListeners() {
        
        // Hide modal when you press close
        this.elem.addEventListener('click', e => {
            if(e.target.classList.contains("close-modal")) {
                this.hide();
            }
        });

        // Also hide modal when you press outside
        //this.elem.addEventListener('click', e => {
        //    if(e.target.classList.contains('modal-azr')) {
        //        this.hide();
        //    }
        //});
    };

}
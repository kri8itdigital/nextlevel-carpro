  class HTMLMapMarker extends  google.maps.OverlayView {
    constructor(args) {
      super();
      this.position = args.latlng;
      this.latlng = args.latlng;
      this.html = args.html;
      this.setMap(args.map);

      if(args.town){
        this.town = args.town;
      }

      if(args.establishment){
        this.establishment = args.establishment;
      }
    }

    createDiv() {
      this.div = document.createElement("div");
      this.div.style.position = "absolute";
      if (this.html) {
        this.div.innerHTML = this.html;
      }

      google.maps.event.addDomListener(this.div, "click", event => {
        google.maps.event.trigger(this, "click");
      });
    }

    appendDivToOverlay() {
      const panes = this.getPanes();
      panes.overlayImage.appendChild(this.div);
    }

    positionDiv() {

      const point = this.getProjection().fromLatLngToDivPixel(this.position);
      let offset_left = this.div.clientWidth/2;
      let offset_top = this.div.clientHeight/2;

      let left = point.x - offset_left;
      let top = point.y - offset_top;

      if (point) {
        this.div.style.left = left+'px';
        this.div.style.top = top+'px';
      }


    }



    draw() {
      if (!this.div) {
        this.createDiv();
        this.appendDivToOverlay();
      }
      this.positionDiv();
    }

    remove() {
      if (this.div) {
        this.div.parentNode.removeChild(this.div);
        this.div = null;
      }
    }

    getPosition() {
      return this.position;
    }

    setPosition() {
      return this.position;
    }

    getDraggable() {
      return false;
    }
  }

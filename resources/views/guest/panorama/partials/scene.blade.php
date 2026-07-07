<!-- A-Frame Scene -->
<a-scene id="panorama-scene" xr-mode-ui="enabled: true; enterVRButton: #btn-vr-custom"
    loading-screen="enabled: false" renderer="antialias: true; sortObjects: true">
    <a-assets id="scene-assets">
        <img id="icon-door" crossorigin="anonymous"
            src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='512' height='512' viewBox='0 0 512 512'><path fill='%23ffffff' d='M320 48v416c0 26.5-21.5 48-48 48H128c-26.5 0-48-21.5-48-48V48C80 21.5 101.5 0 128 0h144c26.5 0 48 21.5 48 48zm-16 0c0-8.8-7.2-16-16-16H128c-8.8 0-16 7.2-16 16v416c0 8.8 7.2 16 16 16h144c8.8 0 16-7.2 16-16V48zm128 0v416c0 26.5-21.5 48-48 48h-16v-32h16c8.8 0 16-7.2 16-16V48c0-8.8-7.2-16-16-16h-16V0h16c26.5 0 48 21.5 48 48zm-96 240c0 13.3-10.7 24-24 24s-24-10.7-24-24 10.7-24 24-24 24 10.7 24 24z'/></svg>">
        <img id="icon-arrow-up" crossorigin="anonymous"
            src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='512' height='512' viewBox='0 0 512 512'><path fill='%23ffffff' d='M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM256 127c9.4 0 18.8 3.8 26.3 11.3l112 112c14.6 14.6 14.6 38.2 0 52.7s-38.2 14.6-52.7 0L256 225 178 303c-14.6 14.6-38.2 14.6-52.7 0s-14.6-38.2 0-52.7l112-112c7.5-7.5 16.9-11.3 26.3-11.3z'/></svg>">
        <img id="icon-arrow-down" crossorigin="anonymous"
            src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='512' height='512' viewBox='0 0 512 512'><path fill='%23ffffff' d='M256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM256 385c-9.4 0-18.8-3.8-26.3-11.3l-112-112c-14.6-14.6-14.6-38.2 0-52.7s38.2-14.6 52.7 0L256 287 334 209c14.6-14.6 38.2-14.6 52.7 0s14.6 38.2 0 52.7l-112 112c-7.5 7.5-16.9 11.3-26.3 11.3z'/></svg>">
        <img id="icon-arrow-right" crossorigin="anonymous"
            src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='512' height='512' viewBox='0 0 512 512'><path fill='%23ffffff' d='M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM385 256c0 9.4-3.8 18.8-11.3 26.3l-112 112c-14.6 14.6-38.2 14.6-52.7 0s-14.6-38.2 0-52.7L287 256 209 178c-14.6-14.6-14.6-38.2 0-52.7s38.2-14.6 52.7 0l112 112c7.5 7.5 11.3 16.9 11.3 26.3z'/></svg>">
        <img id="icon-arrow-left" crossorigin="anonymous"
            src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='512' height='512' viewBox='0 0 512 512'><path fill='%23ffffff' d='M256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM127 256c0-9.4 3.8-18.8 11.3-26.3l112-112c14.6-14.6 38.2-14.6 52.7 0s14.6 38.2 0 52.7L225 256l78 78c14.6 14.6 14.6 38.2 0 52.7s-38.2 14.6-52.7 0l-112-112c-7.5-7.5-11.3-16.9-11.3-26.3z'/></svg>">
        <img id="icon-info" crossorigin="anonymous"
            src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='512' height='512' viewBox='0 0 512 512'><path fill='%23ffffff' d='M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z'/></svg>">
    </a-assets>

    <a-sky id="panorama-sky" radius="500" rotation="0 -90 0"></a-sky>
    <a-light type="ambient" color="#fff" intensity="1"></a-light>

    <a-entity id="camera-rig" position="0 0 0">
        <a-camera id="camera"
            look-controls="pointerLockEnabled: false; magicWindowTrackingEnabled: false; reverseMouseDrag: true"
            wasd-controls="enabled: false" fov="80">
            <a-entity id="cursor" cursor="fuse: false; rayOrigin: mouse"
                raycaster="objects: .clickable; far: 500"
                geometry="primitive: ring; radiusInner: 0.006; radiusOuter: 0.009"
                material="color: #0ea5e9; shader: flat; opacity: 0.8" position="0 0 -1" visible="false"
                animation__fusing="property: scale; startEvents: fusing; easing: easeInCubic; dur: 1200; from: 1 1 1; to: 0.2 0.2 0.2"
                animation__fuseleave="property: scale; startEvents: mouseleave; dur: 200; to: 1 1 1"></a-entity>

            <!-- VR-only fade plane, covers the view during scene transitions inside immersive sessions -->
            <a-plane id="vr-fade" position="0 0 -1" width="4" height="4" material="color: #000; shader: flat; opacity: 0; transparent: true" visible="false"></a-plane>

            <!-- VR-only in-world info panel, replaces the DOM modal inside immersive sessions -->
            <a-entity id="vr-info-panel" position="0 0 -1.5" visible="false">
                <a-plane width="1.6" height="1" color="#111827" opacity="0.9"></a-plane>
                <a-text id="vr-info-title" value="" align="center" width="1.4" position="0 0.38 0.01" color="#38bdf8"></a-text>
                <a-text id="vr-info-body" value="" align="center" width="1.4" wrap-count="34" position="0 0.05 0.01" color="#fff"></a-text>
                <a-plane id="vr-info-close" class="clickable" width="0.4" height="0.14" color="#0ea5e9" position="0 -0.38 0.01">
                    <a-text value="Tutup" align="center" width="2.2" position="0 0 0.01" color="#fff"></a-text>
                </a-plane>
            </a-entity>
        </a-camera>
    </a-entity>

    <a-entity id="hotspots-container"></a-entity>
</a-scene>

<!DOCTYPE html>

<html>

<head>
    <title>AR</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <script src="https://aframe.io/releases/1.0.4/aframe.min.js"></script>
    <script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar.js"></script>
    <script src="/js/gesture-detector.js"></script>
    <script src="/js/gesture-handler.js"></script>
    
    <style>
        #ar-description {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.4;
            max-height: 150px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        #ar-description h4 {
            margin: 0 0 10px 0;
            color: #fff;
            font-size: 18px;
        }
        
        #ar-description p {
            margin: 0;
            color: #ccc;
        }
        
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <a-scene arjs embedded renderer="logarithmicDepthBuffer: true;" vr-mode-ui="enabled: false" gesture-detector
        id="scene">
        <a-assets>
            @foreach($arObjects as $object)
                <a-asset-item id="{{ $object->object_id }}-model" src="/storage/{{ $object->path_obj }}"></a-asset-item>
            @endforeach
        </a-assets>

        @foreach($arObjects as $object)
        <!-- {{ $object->nama }} marker -->
        <a-marker type="pattern" url="/storage/{{ $object->path_patt }}" 
                  raycaster="objects: .clickable" emitevents="true"
                  cursor="fuse: false; rayOrigin: mouse;" 
                  id="marker{{ $object->object_id }}"
                  data-object-name="{{ $object->nama }}"
                  data-object-description="{{ $object->deskripsi }}">
            <a-entity id="{{ $object->object_id }}-entity" 
                      gltf-model="#{{ $object->object_id }}-model" 
                      position="0 0 0" 
                      scale="{{ $object->scale_string }}"
                      class="clickable" gesture-handler>
            </a-entity>
        </a-marker>
        @endforeach

        <a-entity camera></a-entity>
    </a-scene>
    
    <!-- Description overlay -->
    <div id="ar-description">
        <h4 id="ar-title"></h4>
        <p id="ar-text"></p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const descriptionDiv = document.getElementById('ar-description');
            const titleElement = document.getElementById('ar-title');
            const textElement = document.getElementById('ar-text');
            let lastVisibleObject = null;
            let visibleObjects = new Set();

            // Get all markers
            const markers = document.querySelectorAll('a-marker');
            
            markers.forEach(marker => {
                // When marker becomes visible
                marker.addEventListener('markerFound', function() {
                    const objectName = this.getAttribute('data-object-name');
                    const objectDescription = this.getAttribute('data-object-description');
                    
                    console.log('Marker found:', objectName);
                    
                    // Add to visible objects
                    visibleObjects.add({
                        name: objectName,
                        description: objectDescription,
                        timestamp: Date.now()
                    });
                    
                    // Update display with the most recent one
                    lastVisibleObject = {
                        name: objectName,
                        description: objectDescription
                    };
                    
                    updateDescription();
                });
                
                // When marker becomes invisible
                marker.addEventListener('markerLost', function() {
                    const objectName = this.getAttribute('data-object-name');
                    
                    console.log('Marker lost:', objectName);
                    
                    // Remove from visible objects
                    visibleObjects = new Set([...visibleObjects].filter(obj => obj.name !== objectName));
                    
                    // If this was the last visible object, find the next most recent
                    if (lastVisibleObject && lastVisibleObject.name === objectName) {
                        if (visibleObjects.size > 0) {
                            // Get the most recent visible object
                            const mostRecent = [...visibleObjects].reduce((latest, current) => 
                                current.timestamp > latest.timestamp ? current : latest
                            );
                            lastVisibleObject = {
                                name: mostRecent.name,
                                description: mostRecent.description
                            };
                        } else {
                            lastVisibleObject = null;
                        }
                    }
                    
                    updateDescription();
                });
            });
            
            function updateDescription() {
                if (lastVisibleObject && lastVisibleObject.description) {
                    titleElement.textContent = lastVisibleObject.name;
                    textElement.textContent = lastVisibleObject.description;
                    descriptionDiv.style.display = 'block';
                } else {
                    descriptionDiv.style.display = 'none';
                }
            }
        });
    </script>
</body>

</html>

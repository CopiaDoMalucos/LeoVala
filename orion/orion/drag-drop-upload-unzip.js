            /* Drag'n drop stuff */
            var drag = document.getElementById("drag");
            
            drag.ondragover = function(e) {e.preventDefault()}
            drag.ondrop = function(e) {
                e.preventDefault();
                  var length = e.dataTransfer.items.length;
                  for (var i = 0; i < length; i++) {
                    var entry = e.dataTransfer.items[i].webkitGetAsEntry();
                    var file = e.dataTransfer.files[i];
                    var zip = file.name.match(/\.zip/);
                    if (entry.isFile) {
                        if(zip){
                            unzip(file);
                        } else {
                          var file = e.dataTransfer.files[i];

                          if(file.type.match(/image.*/)){
                            upload(file);
                          } else {
                            document.getElementById("error").innerHTML = file.name+" is not an image.";
                          }                       
                        }


                    } else if (entry.isDirectory) {
                     traverseFileTree(entry);
                    }
                  }
            }

            //model for zip.js
            var model = (function() {

                return {
                    getEntries : function(file, onend) {
                        zip.createReader(new zip.BlobReader(file), function(zipReader) {
                            zipReader.getEntries(onend);
                        }, onerror);
                    },
                    getEntryFile : function(entry, creationMethod, onend, onprogress) {
                        var writer, zipFileEntry;

                        function getData() {
                            entry.getData(writer, function(blob) {

                            //read the blob, grab the base64 data, send to upload function
                            oFReader = new FileReader()
                            oFReader.onloadend = function(e) {
                              upload(this.result.split(',')[1]);    
                            };
                            oFReader.readAsDataURL(blob);
                         
                            }, onprogress);
                        }
                            writer = new zip.BlobWriter();
                            getData();
                    }
                };
            })();




            /* Traverse through files and directories */
            function traverseFileTree(item, path) {
              path = path || "";
              if (item.isFile) {
                // Get file
                item.file(function(file) {
                    if(file.type.match(/image.*/)){
                        upload(file);
                    }
                });
              } else if (item.isDirectory) {
                // Get folder contents
                var dirReader = item.createReader();
                dirReader.readEntries(function(entries) {
                  for (var i=0; i<entries.length; i++) {
                    traverseFileTree(entries[i], path + item.name + "/");
                  }
                });
              }
            }

            /* Main unzip function */
            function unzip(zip){
                model.getEntries(zip, function(entries) {
                    entries.forEach(function(entry) {
                        model.getEntryFile(entry, "Blob");
                    });
                });
            }


            /* main upload function that sends images to imgur.com */
            function upload(file) {

                document.body.className = "uploading";

                /* Lets build a FormData object*/
                var fd = new FormData();
                
                fd.append("image", file);
                fd.append("key", "6528448c258cff474ca9701c5bab6927");
                var xhr = new XMLHttpRequest();
                var output = document.getElementById("output");

                xhr.open("POST", "http://api.imgur.com/2/upload.json");
                xhr.onload = function() {

                    if(this.status==400){
                       document.getElementById("error").innerHTML = JSON.parse(xhr.responseText).error.message;
                    } else {
                        var links = JSON.parse(xhr.responseText).upload.links;
                        var dimage = links.small_square;
                        var dlink = links.imgur_page;

                        var a = document.createElement("a");
                        a.href = dlink;

                        var img = document.createElement("img");
                        img.src = dimage;

                        a.appendChild(img);
                        output.appendChild(a);

                        document.body.className = "uploaded";
                    }

                }

                xhr.send(fd);
            }

<?php

namespace MVC\Forms;


use MVC\Core\Model;


class ImageField extends BaseField
{
    const TYPE_FILE = 'file';

    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_FILE;
        parent::__construct($model, $attribute);
    }

    public function renderInput()
    {
        $js = "
        <script>
            let dropArea = document.getElementById('drop-area');
            let fileElem = document.getElementById('fileElem');
            let gallery = document.getElementById('gallery');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });

            dropArea.addEventListener('drop', handleDrop, false);

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight(e) {
                dropArea.classList.add('highlight');
            }

            function unhighlight(e) {
                dropArea.classList.remove('highlight');
            }

            function handleDrop(e) {
                let dt = e.dataTransfer;
                let files = dt.files;
                handleFiles(files);
            }

            dropArea.addEventListener('click', () => {
                fileElem.click();
            });

            fileElem.addEventListener('change', function (e) {
                handleFiles(this.files);
            });

            function handleFiles(files) {
                previewFile(files[0]);
            }

            function previewFile(file) {
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function () {
                    let oldImg = document.getElementById('childGallery');
                    let img = document.createElement('img');
                    img.src = reader.result;
                    img.className += 'img-fluid';
                    img.width = '960';
                    img.height = '650';
                    img.id = 'childGallery';
                    gallery.removeChild(oldImg);
                    gallery.appendChild(img);
                }
            }
        </script>
        ";
        $input = '
            <div class="card">
                <div class="card-body">
                    <div id="drop-area" class="border rounded d-flex justify-content-center align-items-center"
                        style="height: 200px; cursor: pointer">
                        <div class="text-center">
                        <i class="text-primary" style="font-size: 48px"></i>
                        <p class="mt-3">
                            Select your image here.
                        </p>
                        </div>
                    </div>
                    <input type="%s" id="fileElem" name="%s" accept="image/*" class="d-none" />
                    <div class="container mt-2">
                        <div id="gallery" class="row">
                            <img id="childGallery">
                        </div>
                    </div>
                </div>
            </div>
        ';
        if ($this->model->{$this->attribute}) {
            $input = '
                <div class="card">
                    <div class="card-body">
                        <div id="drop-area" class="border rounded d-flex justify-content-center align-items-center"
                            style="height: 200px; cursor: pointer">
                            <div class="text-center">
                            <i class="text-primary" style="font-size: 48px"></i>
                            <p class="mt-3">
                                Select your image here.
                            </p>
                            </div>
                        </div>
                        <input type="%s" id="fileElem" name="%s" accept="image/*" class="d-none" />
                        <div class="container mt-2">
                            <div id="gallery" class="row">
                                <img id="childGallery" src="%s">
                            </div>
                        </div>
                    </div>
                </div>
            ';
            return sprintf($input.$js,
                $this->type,
                $this->attribute,
                $this->model->{$this->attribute},
            );
        }
        return sprintf($input.$js, $this->type, $this->attribute);
    }
}
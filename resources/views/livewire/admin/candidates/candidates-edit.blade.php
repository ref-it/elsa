<div class="flex flex-col p-6 sm:p-8 overflow-y-auto h-full">
    <div class="xl:flex xl:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold leading-6 text-zinc-800 dark:text-white">{{ __('admin.candidatesEditTitle') }}</h1>
        </div>
    </div>
    <div class="mt-6 mb-12">
        <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
            <flux:field>
                <flux:label>{{ __('admin.firstname') }}</flux:label>
                <flux:input type="text" wire:model="firstname" class="w-full" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.lastname') }}</flux:label>
                <flux:input type="text" wire:model="lastname" class="w-full" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.emailAddress') }}</flux:label>
                <flux:input type="text" wire:model="email" class="w-full" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.faculty') }}</flux:label>
                <flux:select variant="listbox" searchable wire:model="faculty">
                    @foreach($faculties as $faculty)
                        <flux:select.option value="{{ $faculty->id }}">{{ json_decode($faculty->name)[0] }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.course') }}</flux:label>
                <flux:select variant="listbox" searchable wire:model="course">
                    @foreach($courses as $course)
                        <flux:select.option value="{{ $course->id }}">{{ json_decode($course->name)[0] }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>
        <div class="mt-6 grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
            <flux:field>
                <flux:label>{{ __('admin.election') }}</flux:label>
                <flux:select variant="listbox" searchable wire:model.live.change="election">
                    @foreach($elections as $election)
                        <flux:select.option value="{{ $election->id }}">{{ json_decode($election->name)[0] }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.committee') }}</flux:label>
                <flux:select variant="listbox" searchable wire:model.live.change="committee">
                    @foreach($committees as $committee)
                        <flux:select.option value="{{ $committee->id }}">{{ json_decode($committee->name)[0] }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
            @if(count($lists) > 0)
                <flux:field>
                    <flux:label>{{ __('admin.list') }}</flux:label>
                    <flux:select variant="listbox" searchable wire:model="list" placeholder="{{ __('common.selectAnOption') }}">
                        @foreach($lists as $list)
                            <flux:select.option value="{{ $list->id }}">{{ json_decode($list->name)[0] }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>
            @endif
        </div>
        <div class="mt-6">
            <flux:table class="w-full">
                <flux:table.columns>
                    <flux:table.column>{{ __('admin.answersDE') }}</flux:table.column>
                    <flux:table.column>{{ __('admin.answersEN') }}</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($questions[0] as $index => $question)
                        <flux:table.row>
                            <flux:table.cell>
                                <div class="card-header overflow-x-auto">{{ $questions[0][$index] }}</div>
                                <flux:textarea wire:model="answersDE.{{ $index }}" class="h-24 rounded-t-none! font-mono" />
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="card-header overflow-x-auto">{{ $questions[1][$index] }}</div>
                                <flux:textarea wire:model="answersEN.{{ $index }}" class="h-24 rounded-t-none! font-mono" />
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
        <div class="mt-6 grid sm:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>{{ __('admin.candidacyReceived') }}</flux:label>
                <flux:input type="datetime-local" wire:model="candidacyReceived" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.approved') }}</flux:label>
                <flux:checkbox wire:model="approved" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.votes') }}</flux:label>
                <flux:input type="number" wire:model="votes" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.resigned') }}</flux:label>
                <flux:checkbox wire:model="resigned" />
            </flux:field>
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-4">
            <flux:button icon="ban" wire:navigate href="{{ url()->previous() }}" class="btn-neutral">
                {{ __('common.cancel') }}
            </flux:button>
            <flux:button variant="primary" icon="save" wire:click="save" class="btn-primary">
                {{ __('common.save') }}
            </flux:button>
        </div>
    </div>
    <div x-data="cropper">
        @if ($pictureUrl)
            <img src="{{ $pictureUrl }}" class="max-h-60 rounded-md shadow-sm">
            <div class="flex gap-x-4 mt-6">
                <flux:button variant="danger" icon="trash-2" @click="removeImage">
                    {{ __('admin.delete') }}
                </flux:button>
            </div>
        @else
            <flux:field>
                <flux:label>{{ __('admin.addPicture') }}</flux:label>
                <input
                    id="imageInput"
                    type="file"
                    accept="image/*"
                    class="w-full h-60 px-3 py-2 border border-zinc-200 rounded-md cursor-pointer"
                    :value="imageCropped"
                    x-show="!imageIsSelected"
                    x-on:change="loadImage"
                >
                <img id="image" class="max-w-full h-60" x-show="imageIsSelected">
            </flux:field>
            <div class="flex justify-end gap-x-4 mt-6">
                <flux:button icon="ban" @click="cancelImage">
                    {{ __('common.cancel') }}
                </flux:button>
                <flux:button variant="primary" icon="save" @click="cropImage">
                    {{ __('admin.cropAndSave') }}
                </flux:button>
            </div>
        @endif
    </div>
</div>

@script
<script>
Alpine.data('cropper', () => {
    return {
        img: null,
        imgInput: null,
        file: null,
        imageFile: null,
        imageCropped: null,
        imageIsSelected: false,
        cropper: null,
        loadImage() {
            this.img = document.getElementById('image');
            if (this.cropper != null) {
                this.cropper.destroy();
            }

            this.cropper = new Cropper(this.img, {
                zoomable: false,
            });

            this.file = event.target.files[0]

            if (this.file.type.indexOf('image/') === -1) {
                alert('Bitte wähle eine Bild-Datei aus. / Please select an image file.')
                return
            }

            if (typeof FileReader === 'function') {
                const reader = new FileReader()

                reader.onload = (event) => {
                    this.imageFile = event.target?.result
                    this.cropper.replace(event.target?.result)
                };

                reader.readAsDataURL(this.file);
                this.imageIsSelected = true;
            } else {
                alert('Dein Browser scheint die FileReader-API nicht zu unterstützen. / Your browser does not seem to support the FileReader API.')
            }
        },
        cropImage() {
            $wire.imageCropped = this.cropper.getCroppedCanvas().toDataURL('image/jpeg')
            $wire.saveImage()
        },
        removeImage() {
            $wire.removeImage()
            this.file = null
            this.imageFile = null
            this.imageCropped = null
            if (this.cropper != null) {
                this.cropper.destroy()
            }
            this.imageIsSelected = false
        },
        cancelImage() {
            this.file = null
            this.imageFile = null
            this.imageCropped = null
            if (this.cropper != null) {
                this.cropper.destroy()
            }
            this.imageIsSelected = false
        }
    }
})
</script>
@endscript
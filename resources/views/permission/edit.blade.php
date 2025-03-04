<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Permission') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form id="edit_permission_form" action="{{route('permission.update')}}">
                        @csrf
                        <!-- Permission Name -->
                        <div>
                            <input type="hidden" id="id" name="id" value="{{$permission->id}}">
                            <x-input-label for="permission" :value="__('Edit Permission')" />
                            <x-text-input id="permission" value="{{$permission->name}}" class="block mt-1 w-full" type="text" name="permission" required/>
                            <x-input-error :messages="$errors->get('permission')" class="mt-2" />
                        </div>
                        <br>
                        
                        <div style="padding-left: 66rem">
                            <x-primary-button class="ms-2">
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        $('#edit_permission_form').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '{{ route('permission.update') }}',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.message) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.replace("{{ route('permission.list') }}");
                        }, 1000);
                    } else {
                        toastr.success("Permission Added Successfully!");
                    }
                    // $("#refresh_div").load(location.href + " #refresh_div");
                },
                error: function(xhr, status, error) {
                    let errorMsg = "An error occurred. Please try again.";
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    toastr.error(errorMsg);
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>


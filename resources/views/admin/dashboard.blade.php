@extends('layouts.app')

@section('content')
<div class="container-fluid my-4">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-3">
            <div class="list-group" id="adminTabs" role="tablist">
                <a class="list-group-item list-group-item-action active" id="users-tab" data-bs-toggle="list" href="#users" role="tab" style="background-color: #800020; color: white; border-color: #800020;">👥 Users</a>
                <a class="list-group-item list-group-item-action" id="equipment-tab" data-bs-toggle="list" href="#equipment" role="tab" style="background-color: #f8f9fa; color: #800020; border-color: #f8f9fa;">🔧 Equipment</a>
                <a class="list-group-item list-group-item-action" id="spares-tab" data-bs-toggle="list" href="#spares" role="tab" style="background-color: #f8f9fa; color: #800020; border-color: #f8f9fa;">🏭 Spare Parts</a>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="col-md-9">
            <div class="tab-content" id="adminTabContent">
                <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
                    @include('admin.partials.users')
                </div>
                <div class="tab-pane fade" id="equipment" role="tabpanel" aria-labelledby="equipment-tab">
                    @include('admin.partials.equipment')
                </div>
                <div class="tab-pane fade" id="spares" role="tabpanel" aria-labelledby="spares-tab">
                    @include('admin.partials.spares')
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Include User Modal --}}
@include('admin.partials.modals.add-user')

{{-- Add JavaScript for dynamic background color change --}}
<script>
    // Get all tab links
    const tabLinks = document.querySelectorAll('.list-group-item');

    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Remove 'active' class from all tabs
            tabLinks.forEach(tab => {
                tab.classList.remove('active');
                tab.style.backgroundColor = '#f8f9fa'; // Reset to default background color
                tab.style.color = '#800020'; // Reset text color to primary
            });

            // Add 'active' class to the clicked tab and change background color
            link.classList.add('active');
            link.style.backgroundColor = '#800020'; // Set to primary background color
            link.style.color = 'white'; // Change text color to white
        });
    });
</script>

@endsection

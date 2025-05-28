{{-- resources/views/admin/company/create.blade.php --}}
{{-- ... other form fields ... --}}

<div>
    <label for="website">Website Perusahaan</label>
    <input type="url" name="website" id="website" value="{{ old('website') }}" required>
    @error('website')
        <span>{{ $message }}</span>
    @enderror
</div>

<div>
    <label for="logo_path">Logo Perusahaan</label>
    <input type="file" name="logo_path" id="logo_path" required accept="image/*">
    @error('logo_path')
        <span>{{ $message }}</span>
    @enderror
</div>

{{-- ... other form fields and submit button ... --}}
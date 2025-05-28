{{-- resources/views/admin/company/edit.blade.php --}}
{{-- ... other form fields ... --}}

<div>
    <label for="website">Website Perusahaan</label>
    <input type="url" name="website" id="website" value="{{ old('website', $company->website) }}" required>
    @error('website')
        <span>{{ $message }}</span>
    @enderror
</div>

<div>
    <label for="logo_path">Logo Perusahaan (Kosongkan jika tidak ingin mengubah)</label>
    @if($company->logo_path)
        <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Current Logo" width="100">
    @endif
    <input type="file" name="logo_path" id="logo_path" accept="image/*">
    @error('logo_path')
        <span>{{ $message }}</span>
    @enderror
</div>

{{-- ... other form fields and submit button ... --}}
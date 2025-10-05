describe('Smoke Test - Laravel App', () => {

  it('Homepage dapat diakses', () => {
    cy.visit('http://127.0.0.1:8000')   // ganti sesuai alamat project Laravel kamu
    cy.contains('Laravel')              // cek teks "Laravel" muncul di halaman
  })

  it('Halaman login dapat diakses', () => {
    cy.visit('http://127.0.0.1:8000/login')  // buka halaman login Laravel
    cy.get('input[name="username"]').should('exist') // pastikan ada field email
    cy.get('input[name="password"]').should('exist') // pastikan ada field password
  })
})

describe('CRUD Test - Laravel App', () => {

  // ==========================================
  // Add Student
  // ==========================================
  it('Dapat menambahkan Mahasiswa baru', () => {
    cy.visit('http://127.0.0.1:8000/admin/data-mahasiswa/create')

    cy.get('input[name="nim"]').type('2141720001')
    cy.get('input[name="name"]').type('Budi Mahasiswa')
    cy.get('input[name="Email"]').type('budi.mahasiswa@simmagang.test	')
    cy.get('input[name="Prodi"]').type('Teknologi Informasi')
    cy.get('input[name="class"]').type('TI-3A')
    cy.get('button[type="submit"]').click()

    cy.contains('Student berhasil ditambahkan').should('exist')
  })

  // ==========================================
  // Add Dospem
  // ==========================================
  it('Dapat menambahkan Dospem baru', () => {
    cy.visit('http://127.0.0.1:8000/dospem/create')

    cy.get('input[name="nip"]').type('123456789')
    cy.get('input[name="nama"]').type('')
    cy.get('input[name="mata_kuliah"]').type('')
    cy.get('button[type="submit"]').click()

    cy.contains('Dospem berhasil ditambahkan').should('exist')
  })

  // ==========================================
  // Add Company
  // ==========================================
  it('Dapat menambahkan Company baru', () => {
    cy.visit('http://127.0.0.1:8000')

    cy.get('input[name="nama"]').type('')
    cy.get('textarea[name="alamat"]').type('')
    cy.get('textarea[name="bio"]').type('')
    cy.get('button[type="submit"]').click()

    cy.contains('Company berhasil ditambahkan').should('exist')
  })

  // ==========================================
  // Edit Profil
  // ==========================================
  it('Dapat mengedit profil mahasiswa', () => {
    cy.visit('http://127.0.0.1:8000')

    cy.get('input[name="nim"]').clear().type('2241720001')
    cy.get('input[name="name"]').clear().type('Fahreiza Taura')
    cy.get('input[name="class"]').clear().type('TI-3I')
    
    // Upload foto profil
    const filePath = 'images/profile.jpg'   // pastikan file ada di folder cypress/fixtures/images/
    cy.get('input[type="file"]').attachFile(filePath)

    cy.get('button[type="submit"]').click()
    cy.contains('Profil berhasil diperbarui').should('exist')
  })
})

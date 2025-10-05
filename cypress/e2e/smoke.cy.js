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
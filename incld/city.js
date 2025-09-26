var config = {
    cUrl: 'https://api.countrystatecity.in/v1/countries',
    ckey: 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA=='
}

var stateSelect = document.querySelector('.state'),
    citySelect = document.querySelector('.city')

function loadStates() {
stateSelect.disabled = false
citySelect.disabled = false
    stateSelect.style.pointerEvents = 'auto'
    citySelect.style.pointerEvents = 'none'


    stateSelect.innerHTML = '<option value="">Select State</option>' // for clearing the existing states
    citySelect.innerHTML = '<option value="">Select City</option>' // Clear existing city options

    fetch(`${config.cUrl}/IN/states`, {headers: {"X-CSCAPI-KEY": config.ckey}})
    .then(response => response.json())
    .then(data => {
        // console.log(data);

        data.forEach(state => {
            const option = document.createElement('option')
            option.value = state.iso2
            option.textContent = state.name 
            stateSelect.appendChild(option)
        })
    })
    .catch(error => console.error('Error loading countries:', error))
}

function loadCities() {
 stateSelect.disabled = false
citySelect.disabled = false

    citySelect.style.pointerEvents = 'auto'
    const selectedStateCode = stateSelect.value

    citySelect.innerHTML = '<option value="">Select City</option>' // Clear existing city options

    fetch(`${config.cUrl}/IN/states/${selectedStateCode}/cities`, {headers: {"X-CSCAPI-KEY": config.ckey}})
    .then(response => response.json())
    .then(data => {
        // console.log(data);

        data.forEach(city => {
            const option = document.createElement('option')
            option.value = city.iso2
            option.textContent = city.name 
            citySelect.appendChild(option)
        })
    })
}
stateSelect.disabled = false
citySelect.disabled = false
window.onload = loadStates

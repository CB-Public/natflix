"use strict"

document.addEventListener("DOMContentLoaded", function (event) {
    event.preventDefault()
    event.stopPropagation()

    const apikey = "972c8b6e";
    const para_search = "&s="
    const page_param = "&page="
    const page_number = "1"
    const url = "http://www.omdbapi.com/?apikey=972c8b6e" + page_param + page_number + para_search

    const app = Vue.createApp({

        data() {
            return {
                pages: 1,
                loadingChecker: false,
                url: "http://www.omdbapi.com/?apikey=972c8b6e", //url: "http://www.omdbapi.com/?apikey=972c8b6e&page=" + this.pages + "&s=",
                totalpages: 0,
                search: "",
                movies: [],
                tab: "search",
                favmovtab: [],
                sendBody: {
                    imdbID_key: "",
                    title_key: "",
                    poster_key: "",
                    genre_key: "",
                    plot_key: "",
                    year_key: "",
                    rating_key: "",
                    currentUserId: ""
                },
                toggle: true,
                api_resp_obj: {},
                replace_ids: [],
                currentClickMovie: null,
                currentUserActive: null,
                currentUserIDFromSQL: "",
                getUserFromDb: [],
                checkreg: false,
                logname: "",
                logpw: "",
                reslogin: false,
                regname: "",
                regpw: "",
                regpw_2: "",
                regwarning: "",
                warnOfNotLoggedIn: "",
                checkSetTimeOut: false,
                checkFetch: false,

                date1: "",
                date2: "",
                resultDate: "",
            }
        },

        watch: {
            search() {
                clearTimeout(this.searchThrottleTO);
                this.page = 1
                this.searchThrottleTO = setTimeout(() => {
                    this.fetchMoviesAPI();
                    this.loadingChecker = true
                }, 500);
            }
        },

        computed: {
            getMovies() {
                return this.movies;
            },
            getFavorites() {
                return this.favmovtab;
            },
        },

        methods: {

            fetchMoviesAPI() {
                let page = 1
                this.pages = page
                this.url = "http://www.omdbapi.com/?apikey=972c8b6e&page=" + this.page + "&s="
                axios.get(this.url + this.search)
                    .then(response => {
                        if (response.data.Response === "True") {
                            this.loadingChecker = false
                            this.movies = response.data.Search

                            // Inneres Request um Details zu bekommen und sofort einzufügen
                            this.getAllDetails(this.movies)

                            this.totalpages = Math.floor(parseInt(response.data.totalResults) / parseInt(response.data.Search.length))

                        } else {
                            // console.error("Die Suche ergab keine Ergebnisse")
                            this.movies = []
                        }
                    })
                    .catch(error => {
                        // console.error("Fehler beim Abrufen der Daten:", error);
                    })
                this.fetchDataFromDatabase();
            },



            getAllDetails(value) {
                if (this.movies == "") return
                value.forEach(element => {
                    const ak = "972c8b6e";
                    const para_id = "&i="
                    const url_id = "http://www.omdbapi.com/?apikey=" + ak + para_id


                    axios.get(url_id + element.imdbID)
                        .then((response) => {
                            if (response.data.Response === "True") {

                                element.plot = response.data.Plot
                                element.imdbRating = response.data.imdbRating

                            } else {
                                // console.error("Es konnten keine Information abgerufen werden")
                            }
                        })
                        .catch(error => {
                            // console.error("Fehler beim Abrufen der Daten:", error);
                        })
                });
            },



            nextpage() {

                if (this.pages == this.totalpages) return
                this.pages++
                this.url = "http://www.omdbapi.com/?apikey=972c8b6e&page=" + this.pages + "&s="
                axios.get(this.url + this.search)
                    .then(response => {
                        if (response.data.Response === "True") {
                            this.movies = response.data.Search
                            this.getAllDetails(this.movies)

                        } else {
                            // console.error("Die Suche ergab keine Ergebnisse")
                            this.movies = []
                        }
                    })
                    .catch(error => {
                        // console.error("Fehler beim Abrufen der Daten:", error);
                    })
                this.fetchDataFromDatabase();
            },

            prevpage() {

                if (this.pages == 1) return
                this.pages--
                this.url = "http://www.omdbapi.com/?apikey=972c8b6e&page=" + this.pages + "&s="
                axios.get(this.url + this.search)
                    .then(response => {
                        if (response.data.Response === "True") {
                            this.movies = response.data.Search
                            this.getAllDetails(this.movies)

                        } else {
                            // console.error("Die Suche ergab keine Ergebnisse")
                            this.movies = []
                        }
                    })
                    .catch(error => {
                        // console.error("Fehler beim Abrufen der Daten:", error);
                    })
                this.fetchDataFromDatabase();

            },


            SendData_FromObject(value) {
                if (this.checkSetTimeOut) return

                if (this.currentUserActive == null) {
                    this.warnOfNotLoggedIn = "Bitte erst einloggen, um Filme o.Ä. zu favorisieren!"
                    this.checkSetTimeOut = true

                    setTimeout(() => {
                        this.checkSetTimeOut = false
                        this.warnOfNotLoggedIn = ""
                    }, 3000)
                    return

                }

                if (this.currentUserActive) {
                    const ak = "972c8b6e";
                    const para_id = "&i="
                    const url_id = "http://www.omdbapi.com/?apikey=" + ak + para_id


                    axios.get(url_id + value.imdbID)
                        .then((response) => {
                            if (response.data.Response === "True") {

                                this.sendBody = {
                                    imdbID_key: response.data.imdbID,
                                    title_key: response.data.Title,
                                    poster_key: response.data.Poster,
                                    genre_key: response.data.Genre,
                                    plot_key: response.data.Plot,
                                    year_key: response.data.Year,
                                    rating_key: response.data.imdbRating,
                                    currentUserId: this.currentUserIDFromSQL,
                                }

                                fetch("../PHP/sql.php", {
                                    method: "POST",
                                    headers: {
                                        "Content-type": "application/json; charset=UTF-8",
                                    },

                                    body: JSON.stringify(this.sendBody),

                                }).then(function (res) {
                                    return res.text();
                                }).then(data => {
                                    this.fetchDataFromDatabase();
                                })
                            } else {
                                // console.error("Es konnten keine Information abgerufen werden")
                            }
                        })
                        .catch(error => {
                            // console.error("Fehler beim Abrufen der Daten:", error);
                        })




                } else {
                    // console.error("Konnte nicht gesendet werden")
                }

            },


            fetchDataFromDatabase() {

                if (this.currentUserActive) {
                    fetch("../PHP/sql.php", {
                        method: "POST",
                        body: JSON.stringify(
                            {
                                keyword: "fav_insert",
                                user_id: this.currentUserIDFromSQL,
                            })
                    })
                        .then(response => {
                            return response.text()
                        })
                        .then(data => {
                            if (data !== null) {

                                this.favmovtab = JSON.parse(data)
                                this.favmovtab.sort((a, b) => a.genre.toLocaleLowerCase().localeCompare(b.genre.toLocaleLowerCase()))
                                this.getAllDetails(this.movies)

                            }
                            // console.log("this.favmovtab: ", movies)
                        })
                        .catch(error => {
                            // console.error("Fehler beim Senden der Einlog-Daten: ", error)
                        })
                }
            },



            checkitemexists(value) {
                return this.favmovtab.find(item => item.imdbid.toLowerCase() === value.imdbID.toLowerCase()) !== undefined;
            },



            removefav(value) {
                if (this.currentUserActive) {
                    let removeFav = this.favmovtab.filter(obj => obj.imdbid === value.imdbid)

                    fetch("../PHP/sql.php", {
                        method: "POST",
                        headers: {
                            "Content-type": "application/json; charset=UTF-8",
                        },
                        body: JSON.stringify(removeFav),

                    }).then(data => {
                        this.fetchDataFromDatabase();
                    });
                }
            },


            get_allInformation(value) {
                if (!this.store) this.store = []

                if (!this.store[value.imdbID]) {
                    const ak = "972c8b6e";
                    const para_id = "&i="
                    const url_id = "http://www.omdbapi.com/?apikey=" + ak + para_id

                    axios.get(url_id + value.imdbID)
                        .then((response) => {
                            if (response.data.Response === "True") {

                                this.store[value.imdbID] = response.data;
                                this.currentClickMovie = response.data

                            } else {
                                // console.error("Es konnten keine Information abgerufen werden")
                            }
                        })
                        .catch(error => {
                            // console.error("Fehler beim Abrufen der Daten:", error);
                        })
                } else {
                    this.currentClickMovie = this.store[value.imdbID];
                }
            },



            logUserPw() {

                if (this.logname == "" || this.logpw == "") {
                    // alert("Username oder Password falsch")
                    return
                }

                fetch("../PHP/sql.php", {
                    method: "POST",
                    body: JSON.stringify({
                        "keyword": "userlogin",
                        "username": this.logname,
                        "password": this.logpw
                    })
                })
                    .then(response => response.json())
                    .then(data => {

                        this.currentUserActive = data;
                        this.currentUserIDFromSQL = data["user_id"];
                        this.logname = "";
                        this.logpw = "";
                        this.fetchDataFromDatabase();

                    })
                    .catch(error => {
                        // console.error("Fehler beim Senden der Einlog-Daten: ", error)
                    })
            },


            logout() {
                this.currentUserActive = null
                this.warnOfNotLoggedIn = ""
                this.favmovtab = [];
            },


            regUserPw() {
                let specialCharRegex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g
                let upperLetter = /[A-Z]/g
                let numbers = /\d/g

                let specialCharMatches = this.regpw.match(specialCharRegex)
                let specialCharCount = specialCharMatches ? specialCharMatches.length : 0

                let upperMatches = this.regpw.match(upperLetter)
                let upperCount = upperMatches ? upperMatches.length : 0

                let numberMatches = this.regpw.match(numbers)
                let numberCount = numberMatches ? numberMatches.length : 0

                let checkpw = []

                if (this.regname == "") {
                    this.regwarning = "Der Username darf nicht leer sein!"
                    return
                }
                else if (this.regname.length > 40) {
                    this.regwarning = "Username darf maximal 40 Zeichen lang sein"
                    return
                }
                else if (this.regpw == "") {
                    this.regwarning = "Das Passwort darf nicht leer sein!"
                    return
                }
                else if (this.regpw_2 == "") {
                    this.regwarning = "Das 2.Passwortfeld darf nicht leer sein!"
                    return
                }
                else if (this.regpw.length < 8) {
                    this.regwarning = "Das Passwort muss mindestens 8 Zeichen lang sein"
                    return
                }
                else if (upperCount < 1) {
                    this.regwarning = "Es muss mind. 1 Großbuchstabe dabei sein"
                    return
                }
                else if (numberCount < 2) {
                    this.regwarning = "Es müssen mind. 2 Zahlen dabei sein"
                    return
                }
                else if (specialCharCount < 1) {
                    this.regwarning = "Es muss mind. 1 Sonderzeichen enthalten sein"
                    return
                }
                else if (this.regpw !== this.regpw_2) {
                    this.regwarning = "Die Passwörter stimmen nicht überein!"
                    return
                }

                else {
                    fetch("../PHP/sql.php", {
                        method: "POST",
                        body: JSON.stringify({
                            "keyword": "registry",
                            "username": this.regname,
                            "password": this.regpw
                        })
                    })
                        .then(response => response.json())
                        .then(data => {

                            if (data["message"] == "Registierung Erfolgreich!") {
                                this.regwarning = data["message"]
                                this.regpw = "";
                                this.regpw_2 = "";
                                this.regname = "";
                                setTimeout(() => {
                                    this.checkreg = !this.checkreg
                                    this.regwarning = ""
                                }, 2000)
                            }


                            if (data["message"] == "Benutzername bereits vergeben!") {
                                this.regwarning = data["message"]
                                this.regpw = "";
                                this.regpw_2 = "";
                                setTimeout(() => { this.regwarning = ""; data["message"] = "" }, 2000)
                            }

                        })
                        .catch(error => {
                            // console.error("Fehler beim Senden der Registrierungsdaten: ", error)
                        })
                }

            }

        },

        mounted() {
            this.fetchDataFromDatabase();
            this.fetchMoviesAPI();
        },

    })

    app.mount('#app')
})
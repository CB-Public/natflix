<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natflix</title>
    <link rel="icon" type="image/x-icon" href="../Media/Icons/favicon.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.4.21/vue.global.min.js" integrity="sha512-gEM2INjX66kRUIwrPiTBzAA6d48haC9kqrWZWjzrtnpCtBNxOXqXVFEeRDOeVC13pw4EOBrvlsJnNr2MXiQGvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js" integrity="sha512-PJa3oQSLWRB7wHZ7GQ/g+qyv6r4mbuhmiDb8BjSFZ8NZ2a42oTtAq5n0ucWAwcQDlikAtkub+tPVCw4np27WCg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../style.scss" type="text/css">
    <link rel="stylesheet" href="../responsive.scss" type="text/css">



</head>


<body>

    <div id="app">
        <div class="header">

            <div class="space">
                <div v-if="!currentUserActive" class="regbutton">
                    <button type="button" @click="checkreg = !checkreg" class="btn btn-outline-light">Registry</button>
                </div>
                <div v-if="!currentUserActive" class="registsection" :class="{'active': checkreg}">
                    <form class="formreg" :class="{'active': checkreg}" method="POST" action="">
                        <h3>Registry</h3>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" v-model="regname" placeholder="">
                            <label for="floatingInput">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="floatingPassword" v-model="regpw" placeholder="">
                            <label for="floatingPassword">Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="floatingPassword-verify" v-model="regpw_2" placeholder="">
                            <label for="floatingPassword-verify">Password bestätigen</label>
                        </div>
                        <div class="applyreg">
                            <button type="button" @click="regUserPw()" class="btn btn-outline-success">Let's go!</button>
                            <button type="button" @click="checkreg = !checkreg; regwarning = ''" class="btn btn-outline-success">Abbrechen</button>
                        </div>
                        <div class="regwarning" :class="{'active': regwarning !== ''}">
                            {{regwarning}}
                        </div>
                    </form>
                </div>
            </div>


            <div class="titlenameheader">
                <div class="ordertitle">
                    NATFLIX
                </div>
            </div>

            <div class="loginsection" v-if="!currentUserActive" :class="{'active': reslogin}">
                <div class="reslogin">
                    <button v-if="display <= 1023" type="button" class="btn btn-outline-light" @click="reslogin = !relogin">
                        Login
                    </button>
                </div>
                <form class="formlogin" :class="{'active': reslogin}" action="" method="POST" id="userpwform" :class="{'active': reslogin}">
                    <div v-if="!checkreg" class="input-group">
                        <h3 v-if="reslogin">Login</h3>
                        <input type="text" id="usernameid" v-model="logname" name="username" class="form-control" placeholder="Username">
                        <input type="password" id="passwordid" v-model="logpw" name="password" class="form-control" placeholder="Password">
                        <button class="btn btn-outline-secondary" type="button" @click="logUserPw()" id="button-addon1">
                            <svg class="portal" focusable="false" data-prefix="far" data-icon="person-to-portal" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="currentColor" d="M272 96a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm-90.7 12.6c-14-3.5-28.7-3.5-42.7 0l-1.8 .5c-13.3 3.3-25.6 9.7-35.9 18.6L56.4 165.8c-10.1 8.6-11.2 23.8-2.6 33.8s23.8 11.2 33.8 2.6l44.5-38.2c4.7-4 10.3-6.9 16.3-8.4l1.8-.5c6.4-1.6 13-1.6 19.4 0l8.6 2.1-32.7 98c-8.5 25.5 2.3 53.4 25.7 66.5l88 49.5L225.1 480.8c-4 12.7 3.1 26.1 15.7 30.1s26.1-3.1 30.1-15.8L307 379.5c5.6-18-2.1-37.5-18.6-46.8l-32.1-18 28.1-84.4 5.6 18.2c7.2 23.5 28.9 39.5 53.5 39.5H352h16.5H392c13.3 0 24-10.7 24-24s-10.7-24-24-24H368.1c1.2-82.9 11.4-134.5 24.1-164c12.4-28.7 22.4-28.1 23.7-28l.1 0 .1 0c1.3-.1 11.3-.7 23.7 28c13.5 31.4 24.2 87.7 24.2 180s-10.7 148.6-24.2 180c-12.4 28.7-22.4 28.1-23.7 28l-.1 0-.1 0c-1.3 .1-11.3 .7-23.7-28c-10.1-23.4-18.6-60.5-22.2-116H352 321.9c8.8 140.7 47.6 192 94.1 192c53 0 96-66.6 96-256S469 0 416 0c-46.2 0-84.8 50.6-93.9 189.1l-5.8-18.9c-5.8-18.7-20.9-33.1-39.9-37.9l-95-23.7zm70.8 67.2l-38.3 115-19-10.7c-3.3-1.9-4.9-5.9-3.7-9.5L225 169l27.1 6.8zM122.5 317.1L103.4 368H24c-13.3 0-24 10.7-24 24s10.7 24 24 24h84.9c16.7 0 31.6-10.3 37.4-25.9l14.1-37.6-4.9-2.8c-14.1-8-25.4-19.3-33-32.6z" class="" data-darkreader-inline-fill="" style="--darkreader-inline-fill: currentColor;"></path>
                            </svg>
                        </button>
                        <div class="applylog" v-if="reslogin">
                            <button type="button" @click="logUserPw()" class="btn btn-outline-success">Login!</button>
                            <button type="button" @click="reslogin = !reslogin; regwarning = ''" class="btn btn-outline-success">Abbrechen</button>
                        </div>
                    </div>
                </form>
            </div>

            <div v-else class="loginsection" :class="{'active_v2': currentUserActive}">
                <div class="loginshow">
                    <div class="activeuser" v-if="display > 1023">
                        <div>
                            Hallo,&#160
                        </div>
                        <div class="unshow">
                            {{currentUserActive?.username}}
                        </div>
                    </div>
                    <div class="logoutbtn" @click="logout()">
                        <span>
                            <button type="button" class="btn btn-dark">
                                Logout
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="tabbar">
            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home-tab" @click="tab='search'" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                        Suche
                    </button>
                </li>

                <li class="nav-item" role="presentation" @click="fetchDataFromDatabase()">
                    <button class="nav-link" id="pills-profile-tab" @click="tab='favmovies'" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                        Fav Filme
                    </button>
                </li>
            </ul>
        </div>


        <!-- Seach Tab & Content -->
        <div class="section_all">
            <div class="content">
                <div class="search" v-if="tab === 'search'">

                    <div class="search_bar">
                        <input type="text" id="searchinput" v-model="search" class="form-control" name="search" placeholder="Suche hier eingeben">
                    </div>
                    <div class="page" v-if="getMovies.length > 0">
                        <nav>
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" @click="prevpage()">Zurück</a></li>
                                <li class="page-item"><a class="page-link">{{pages}}</a></li>
                                <li class="page-item"><a class="page-link" @click="nextpage()">Nächstes</a></li>
                            </ul>
                        </nav>
                    </div>

                    <div class="movies">
                        <div class="loadingcircle" v-if="loadingChecker">
                            <div class="spinner-border text-danger" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="movie" id="movieid" v-if="(getMovies.length > 0 && (checkreg !== true && reslogin !== true))">
                            <div v-for=" (value, key) in getMovies" :key="key" :id="'movId-' + value.imdbID">
                                <div class="singlemovie">
                                    <div class="movimg">
                                        <img :src="value.Poster" onerror="this.src='../Media/MoviePic/NoImage.jpg'">
                                    </div>

                                    <div class="movinfos">
                                        <div class="movtitle">
                                            <h5>
                                                {{value.Title}}
                                            </h5>
                                        </div>
                                        <div class="movyear">
                                            ({{value.Year}})
                                        </div>
                                        <div class="movplot">
                                            {{value.plot}}
                                        </div>
                                        <div class="movscore">
                                            <p>
                                                IMBD RATING
                                            </p>
                                            <div class="movscoreNr">
                                                <i class="fa-solid fa-star"></i>&#160
                                                <strong>{{value.imdbRating}}</strong>
                                            </div>
                                        </div>
                                        <div class="movbadgefav" :class="{'active': checkitemexists(value)}" @click="SendData_FromObject(value)">
                                            <span class="movbadge">
                                                <i v-if="checkitemexists(value)" class="fa-solid fa-thumbs-up" aria-hidden="true"></i>
                                                <i v-else class="fa-regular fa-thumbs-up"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="getMovies.length < 1">
                            Keine Filme gefunden
                        </div>
                        <div>
                            <div class="warnByClickWOuUser" :class="{'active': warnOfNotLoggedIn !== ''}">
                                {{warnOfNotLoggedIn}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="favmovies" v-if="tab === 'favmovies'">
                    <div v-if="getFavorites.length > 0" class="favmovie">
                        <div v-for="(val_fav, key) in getFavorites" :key="key" class="singlemovie">
                            <div class="movimg">
                                <img :src="val_fav.img_url" onerror="this.src='../Media/MoviePic/NoImage.jpg'">
                            </div>
                            <div class="movinfos">
                                <div class="movtitle">
                                    <h5>
                                        {{val_fav.title}}
                                    </h5>
                                </div>
                                <div class="movyear">
                                    ({{val_fav.year}})
                                </div>
                                <div class="movplot">
                                    {{val_fav.plot}}
                                </div>
                                <div class="movscore">
                                    <p>
                                        IMBD RATING
                                    </p>
                                    <div class="movscoreNr">
                                        <i class="fa-solid fa-star"></i>&#160
                                        <strong>{{val_fav.rating}}</strong>
                                    </div>
                                </div>
                                <div class="movbadgefav" @click="removefav(val_fav)">
                                    <span class="movbadge">
                                        <i class="fa-solid fa-thumbs-up"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        Nach Lieblingsfilmen wird gewartet... 🧐
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script type="module" src="../JS/search.js"></script>
</body>

</html>
var pages = ["index","search","songs","artists","albums","login","playlist"];
pages.forEach(element => {
    if(window.location.pathname.includes(element))
    {
        document.getElementById(element + "li").classList.add("active");
    }
});
if(window.location.pathname == "/")
{
    document.getElementById("indexli").classList.add("active");
}
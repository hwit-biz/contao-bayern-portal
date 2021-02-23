Contao Bayern Portal
====================

This Contao extension allows you to integrate data from the BayernPortal via its [REST API](https://www.baybw-services.bayern.de/export-webservices.htm).

## Usage

### Configuration

After installing this extension there will be a new back end module called _BayernPortal_ under which you can create a configuration. Each configuration takes a name and the credentials for the BayernPortal API.

### Front End Modules

The extension provides 6 different front end modules - one for each "main" entity type of the API, plus some additional ones:

* Ansprechpartner (Contacts)
* Behörden (Administrations)
* Dienststellen (Departments)
* Dienststelle Leistungen (Services of a Department)
* Lebenslagen (Circumstances)
* Leistungen (Services)

Each module will list all records retrieved from the API and provide detail links. Each module is also able to show the details of a record. Some of the modules also have the ability to define a redirect page for some entity types, so that the link to the detail view of that entity can be generated accordingly.

### Templating

For most entities, there exists a list template and a detail template. The naming convention is `bayernportal_<entity-name>_<list-type>` - so the list element template for administrations (Behörden) will be named `bayernportal_behoerde_list` and its detail template will be named `bayernportal_behoerde_detail`. You can override the contents of these templates as usual. For the module itself there only exists one template that is used accross all modules: `mod_bayern_portal`.

In all the BayernPortal templates two special methods are available `$this->renderList()` and `$this->renderDetail()`. These methods take an API entity as its argument and they will automatically render either the list view or the detail view for that entity. See the default templates for their usage.

### Caching

This extension takes advantage of the built in HTTP Cache of Contao and tags the responses according to the elements visible on a page. In order to increase speed and reduce requests to the API, the public cache should be enalbed for the pages which contain any of the aforementioned modules.

However, if you want to invalidate the cache, so that the newest information from the API is shown you do not have to invalidate the whole HTTP cache. Instead this extension provides an additional maintenance module in the back end where you can invalidate the cache for specific entities. So for example, if you changed the details of some contacts (Ansprechpartner), you can then invalidate only the according pages where information about contacts are shown.

## Attributions

The development of this extension was funded by the [City of Gunzenhausen](https://gunzenhausen.de/).

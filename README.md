## Script to create DNS Zone file for an OpenProvider domain

Using this script you can export the DNS zone file for a domain that is hosted at OpenProvider.

You can use this to create a backup of your DNS configuration.


### Setup

```
$ git clone git@github.com:Werkspot/openprovider-dns-export.git
$ cd openprovider-dns-export
$ cp .env-dist .env
$ vim .env
```

### Usage:

```
$ ./export.php somedomain.com

@                   	3600	IN	A 	10.0.0.2
alpha                   86400	IN	A	10.0.0.2
beta                    86400	IN	A	10.0.0.3
charlie             	86400	IN	A	10.0.0.4
delta               	86400	IN	A	10.0.0.5
delta.tst            	10800	IN	CNAME	delta.somedomain.com.
www                  	10800	IN	CNAME	alpha.somedomain.com.
@                   	3600	IN	MX	20 gmail.com
@                   	3600	IN	MX	10 gmail2.com
@                   	10800	IN	TXT	v=spf1 include:_spf.google.com ~all
reply               	3600	IN	MX	20 gmail.com
reply               	3600	IN	MX	10 gmail2.com
```

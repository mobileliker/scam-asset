## 重启后步骤

### 开启Nginx
```
service nginx start
```

### Start Mysql
```
service mysql start
```

### Start Redis
```
systemctl start redis-server
```

### Stop SElinux
```
setenforce 0
```
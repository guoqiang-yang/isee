

CREATE TABLE t_picture (
  id        int(11)         NOT NULL AUTO_INCREMENT,
  pictag    varchar(64)     NOT NULL DEFAULT '' COMMENT '本地：图片pictag(id.type)',
  width     int(11)         NOT NULL DEFAULT '0',
  height    int(11)         NOT NULL DEFAULT '0',
  srcinfo   varchar(256)    NOT NULL DEFAULT '' COMMENT '来源信息(json格式:src_pictag, 裁剪相关信息等)',
  ctime     timestamp       NOT NULL DEFAULT '0000-00-00 00:00:00',
  mtime     timestamp       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='图片存储表';